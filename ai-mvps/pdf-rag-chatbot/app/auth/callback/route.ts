
import { NextRequest, NextResponse } from 'next/server';

export async function GET(request: NextRequest) {
    const searchParams = request.nextUrl.searchParams;
    const token = searchParams.get('token');
    const email = searchParams.get('email');

    if (!token) {
        return NextResponse.json({ error: 'Missing token' }, { status: 400 });
    }

    // In a real production scenario, we would validate this token with the Laravel API here.
    // For "Option B" MVP, we accept the token and set a session cookie.

    // Create response redirecting to home
    const response = NextResponse.redirect(new URL('/', request.url));

    // Set cookie valid for 10 minutes
    response.cookies.set('mvp-access-pdf-rag', token, {
        httpOnly: true,
        secure: process.env.NODE_ENV === 'production',
        sameSite: 'lax',
        maxAge: 600
    });

    if (email) {
        response.cookies.set('mvp-user-email', email, {
            httpOnly: false,
            secure: process.env.NODE_ENV === 'production',
            sameSite: 'lax',
            maxAge: 600
        });
    }

    return response;
}
