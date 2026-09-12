import { NextRequest, NextResponse } from 'next/server';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

export async function GET(request: NextRequest) {
    const token = request.nextUrl.searchParams.get('token');

    if (!token) {
        return NextResponse.json({ error: 'Missing token' }, { status: 400 });
    }

    const response = NextResponse.redirect(new URL('/', request.url));
    response.cookies.set('mvp-access-pdf-rag', token, {
        httpOnly: true,
        secure: process.env.NODE_ENV === 'production',
        sameSite: 'lax',
        maxAge: 600,
    });

    return response;
}
