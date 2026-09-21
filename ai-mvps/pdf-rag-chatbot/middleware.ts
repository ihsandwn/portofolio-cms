import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';

const COOKIE_NAME = 'mvp-access-pdf-rag';
const PUBLIC_API_PATHS = ['/api/health', '/api/auth/verify'];

// Local-only escape hatch. Without a Laravel /ai-lab handoff there is no access cookie,
// so every page request bounced to :8000 and the UI never rendered on localhost.
// When opted in, the first request seeds a session cookie and the normal auth path runs
// from there. Double-gated: inert in a production build, off unless MVP_DEV_BYPASS_AUTH=true.
const DEV_BYPASS =
  process.env.NODE_ENV !== 'production' && process.env.MVP_DEV_BYPASS_AUTH === 'true';
// 64 alphanumeric chars, matching the token shape Laravel issues.
const DEV_TOKEN = `devbypass${'0'.repeat(55)}`;

export function middleware(request: NextRequest) {
  if (DEV_BYPASS && !request.cookies.get(COOKIE_NAME)) {
    const seeded = NextResponse.next();
    seeded.cookies.set(COOKIE_NAME, DEV_TOKEN, {
      httpOnly: true,
      sameSite: 'lax',
      path: '/',
      maxAge: 3600,
    });
    return seeded;
  }

  const token = request.cookies.get(COOKIE_NAME);

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
