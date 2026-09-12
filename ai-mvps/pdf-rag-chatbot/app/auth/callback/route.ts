import { NextRequest, NextResponse } from 'next/server';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

async function verifyToken(token: string): Promise<{ valid: boolean; email?: string }> {
  const endpoint = process.env.LARAVEL_TOKEN_VERIFY_URL;
  if (!endpoint) return { valid: false };
  try {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), Number(process.env.VERIFY_TIMEOUT_MS ?? 5_000));
    try {
      const response = await fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ token }),
        signal: controller.signal,
      });
      if (!response.ok) return { valid: false };
      const data = await response.json();
      return { valid: true, email: data.email };
    } finally { clearTimeout(timeout); }
  } catch { return { valid: false }; }
}

export async function GET(request: NextRequest) {
  const searchParams = request.nextUrl.searchParams;
  const token = searchParams.get('token');

  if (!token) return NextResponse.json({ error: 'Missing token' }, { status: 400 });
  const { valid, email } = await verifyToken(token);
  if (!valid) return NextResponse.json({ error: 'Invalid token' }, { status: 401 });

  const response = NextResponse.redirect(new URL('/', request.url));
  response.cookies.set('mvp-access-pdf-rag', token, { httpOnly: true, secure: process.env.NODE_ENV === 'production', sameSite: 'lax', maxAge: 600 });
  if (email) response.cookies.set('mvp-user-email', email, { httpOnly: false, secure: process.env.NODE_ENV === 'production', sameSite: 'lax', maxAge: 600 });
  return response;
}