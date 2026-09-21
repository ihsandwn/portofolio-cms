import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';

const COOKIE_NAME = 'mvp-access-hr-screening';

// Local-only escape hatch. Without a Laravel /ai-lab handoff there is no access cookie,
// so every page request bounced to :8000 and the UI never rendered on localhost.
// When opted in, the first request seeds a session cookie and the normal auth path runs
// from there. Double-gated: inert in a production build, off unless MVP_DEV_BYPASS_AUTH=true.
const DEV_BYPASS =
    process.env.NODE_ENV !== 'production' && process.env.MVP_DEV_BYPASS_AUTH === 'true';
// 64 alphanumeric chars, matching the token shape accessTokenSchema expects.
const DEV_TOKEN = `devbypass${'0'.repeat(55)}`;

export function middleware(request: NextRequest) {
    const { pathname } = request.nextUrl;

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

    if (
        pathname.startsWith('/auth/callback')
        || pathname === '/api/health'
        || pathname.startsWith('/_next')
        || pathname.includes('.')
    ) {
        return NextResponse.next();
    }

    const token = request.cookies.get(COOKIE_NAME)?.value;
    if (token) {
        return NextResponse.next();
    }

    if (pathname.startsWith('/api')) {
        return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const laravelUrl = process.env.NEXT_PUBLIC_LARAVEL_API_URL;
    if (!laravelUrl) {
        return NextResponse.json({ error: 'Authentication service unavailable' }, { status: 503 });
    }

    return NextResponse.redirect(new URL('/ai-lab', laravelUrl));
}

export const config = {
    matcher: ['/((?!_next/static|_next/image|favicon.ico).*)'],
};
