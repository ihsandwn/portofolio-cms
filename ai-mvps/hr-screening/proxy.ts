import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';

const ACCESS_TOKEN_PATTERN = /^[A-Za-z0-9]{64}$/;

export function proxy(request: NextRequest) {
    const { pathname } = request.nextUrl;

    if (
        pathname.startsWith('/auth/callback')
        || pathname === '/api/health'
        || pathname.startsWith('/_next')
        || pathname.includes('.')
    ) {
        return NextResponse.next();
    }

    const token = request.cookies.get('mvp-access-hr-screening')?.value;
    if (token && ACCESS_TOKEN_PATTERN.test(token)) {
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
