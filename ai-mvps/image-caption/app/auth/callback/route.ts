import { NextRequest, NextResponse } from 'next/server';
import { validateAccessToken } from '@/lib/auth';

export async function GET(request: NextRequest) {
  const token = request.nextUrl.searchParams.get('token');
  if (!token) return NextResponse.json({ error: 'Invalid access request' }, { status: 400 });

  if (!(await validateAccessToken(token, request.nextUrl.origin))) {
    return NextResponse.json({ error: 'Invalid access request' }, { status: 401 });
  }

  const response = NextResponse.redirect(new URL('/', request.url));
  response.cookies.set('mvp-access-image-caption', token, {
    httpOnly: true,
    secure: process.env.NODE_ENV === 'production',
    sameSite: 'lax',
    maxAge: 600,
    path: '/',
  });
  return response;
}
