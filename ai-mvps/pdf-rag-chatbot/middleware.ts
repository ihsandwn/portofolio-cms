import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';

const PUBLIC_API_PATHS = ['/api/health', '/api/auth/verify'];

export function middleware(request: NextRequest) {
  const token = request.cookies.get('mvp-access-pdf-rag');

  if (request.nextUrl.pathname.startsWith('/auth/callback')) return NextResponse.next();

  if (request.nextUrl.pathname.startsWith('/_next') || request.nextUrl.pathname.includes('.')) {
    return NextResponse.next();
  }

  if (request.nextUrl.pathname.startsWith('/api')) {
    const isPublic = PUBLIC_API_PATHS.some((path) => request.nextUrl.pathname === path);
    if (isPublic) return NextResponse.next();
    if (token) return NextResponse.next();
    return NextResponse.json({ error: 'Unauthorized: Access Token Required' }, { status: 401 });
  }

  if (!token) {
    const laravelUrl = process.env.NEXT_PUBLIC_LARAVEL_API_URL || 'http://localhost:8000';
    return NextResponse.redirect(new URL('/ai-lab', laravelUrl));
  }

  return NextResponse.next();
}

export const config = {
  matcher: ['/((?!_next/static|_next/image|favicon.ico).*)'],
};