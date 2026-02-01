
import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';

export function middleware(request: NextRequest) {
    const token = request.cookies.get('mvp-access-pdf-rag');

    // Allow access to the auth callback route itself
    if (request.nextUrl.pathname.startsWith('/auth/callback')) {
        return NextResponse.next();
    }

    // Allow static assets/images/public API
    if (
        request.nextUrl.pathname.startsWith('/_next') ||
        request.nextUrl.pathname.startsWith('/api/public') ||
        request.nextUrl.pathname.includes('.') // Files like favicon.ico, etc.
    ) {
        return NextResponse.next();
    }

    // Check if token exists
    if (!token) {
        // If it's an API request, return 401 JSON
        if (request.nextUrl.pathname.startsWith('/api')) {
            return NextResponse.json({ error: 'Unauthorized: Access Token Required' }, { status: 401 });
        }

        // Redirect back to the CMS Portfolio for Page Requests
        const laravelUrl = process.env.NEXT_PUBLIC_LARAVEL_API_URL || 'http://localhost:8000';
        return NextResponse.redirect(new URL('/ai-lab', laravelUrl));
    }

    return NextResponse.next();
}

export const config = {
    matcher: [
        /*
         * Match all request paths except for:
         * - _next/static (static files)
         * - _next/image (image optimization files)
         * - favicon.ico (favicon file)
         * Note: We now INCLUDE /api routes in the check!
         */
        '/((?!_next/static|_next/image|favicon.ico).*)',
    ],
};
