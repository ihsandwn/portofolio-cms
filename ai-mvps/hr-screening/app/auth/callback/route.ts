import { NextRequest, NextResponse } from 'next/server';
import { validateAccessToken } from '@/lib/auth';

export async function GET(request: NextRequest) {
    const token = request.nextUrl.searchParams.get('token');

    if (!token || !(await validateAccessToken(token))) {
        return NextResponse.json({ error: 'Invalid or expired access token.' }, { status: 401 });
    }

    const response = NextResponse.redirect(new URL('/', request.url));
    response.cookies.set('mvp-access-hr-screening', token, {
        httpOnly: true,
        secure: process.env.NODE_ENV === 'production',
        sameSite: 'lax',
        path: '/',
        maxAge: 600,
    });

    return response;
}
