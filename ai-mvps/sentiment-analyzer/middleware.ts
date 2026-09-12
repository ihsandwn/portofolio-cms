
import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';

export function middleware(request: NextRequest) {
    const token = request.cookies.get('mvp-access-sentiment');

    // Allow access to the auth callback route
    if (request.nextUrl.pathname.startsWith('/auth/callback')) {
        return NextResponse.next();
    }

    // Allow static assets
    if (
        request.nextUrl.pathname.startsWith('/_next') ||
        request.nextUrl.pathname.includes('.')
    ) {
        return NextResponse.next();
    }

    if (!token) {
        // API -> 401
        if (request.nextUrl.pathname.startsWith('/api')) {
            return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
        }

        // Page -> Redirect
        const laravelUrl = process.env.NEXT_PUBLIC_LARAVEL_API_URL || 'http://localhost:8000';
        return NextResponse.redirect(new URL('/ai-lab', laravelUrl));
    }

    return NextResponse.next();
}

export const config = {
    // Include api routes in protection (removed 'api' from exclusion list)
    matcher: ['/((?!_next/static|_next/image|favicon.ico).*)'],
};
