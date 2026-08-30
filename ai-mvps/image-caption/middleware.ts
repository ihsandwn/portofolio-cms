
import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';
import { withRateLimit } from './lib/rate-limit';
import { validateToken } from './lib/auth';

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
            try {
                await validateToken(token.value);
            } catch (error) {
                const response = NextResponse.json({ error: 'Invalid or expired token' }, { status: 401 });
                response.headers.set('WWW-Authenticate', 'Bearer realm="API", error="invalid_token"');
                return response;
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

    try {
        await validateToken(token.value);
        return NextResponse.next();
    } catch (error) {
        const response = NextResponse.json({ error: 'Invalid or expired token' }, { status: 401 });
        response.headers.set('WWW-Authenticate', 'Bearer realm="Website", error="invalid_token"');
        return response;
    }
}

export const config = {
    matcher: ['/((?!_next/static|_next/image|favicon.ico).*)'],
};
