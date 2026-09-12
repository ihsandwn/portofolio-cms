
import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';
import { withRateLimit } from './lib/rate-limit';

export async function middleware(request: NextRequest) {
    // API routes: check token, then rate limit
    if (request.nextUrl.pathname.startsWith('/api')) {
        if (request.nextUrl.pathname.startsWith('/api/health')) {
            return withRateLimit(request, () => NextResponse.next());
        }
        if (request.nextUrl.pathname.startsWith('/api/caption')) {
            const token = request.cookies.get('mvp-access-image-caption');
            if (!token) {
                return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
            }
            return withRateLimit(request, () => NextResponse.next());
        }
        return withRateLimit(request, () => NextResponse.json({ error: 'Unauthorized' }, { status: 401 }));
    }

    // Auth callback route - allow access without token for callback handling
    if (request.nextUrl.pathname.startsWith('/auth/callback')) {
        return NextResponse.next();
    }

    // Static assets
    if (
        request.nextUrl.pathname.startsWith('/_next') ||
        request.nextUrl.pathname.includes('.')
    ) {
        return NextResponse.next();
    }

    const token = request.cookies.get('mvp-access-image-caption');
    if (!token) {
        const laravelUrl = process.env.NEXT_PUBLIC_LARAVEL_API_URL || 'http://localhost:8000';
        return NextResponse.redirect(new URL('/ai-lab', laravelUrl));
    }

    return NextResponse.next();
}

export const config = {
    matcher: ['/((?!_next/static|_next/image|favicon.ico).*)'],
};
