import { NextRequest, NextResponse } from 'next/server';
import { validateAccessToken } from '@/lib/laravel-auth';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

export async function GET(request: NextRequest) {
  const token = request.nextUrl.searchParams.get('token');
  if (!token || !(await validateAccessToken(token, request.nextUrl.origin))) {
    return NextResponse.json({ error: 'Invalid access request' }, { status: 401 });
  }

  const response = NextResponse.redirect(new URL('/', request.url));
  response.cookies.set('mvp-access-pdf-rag', token, {
    httpOnly: true,
    secure: process.env.NODE_ENV === 'production',
    sameSite: 'lax',
    path: '/',
    maxAge: 600,
  });
  return response;
}
