import { NextRequest, NextResponse } from 'next/server';
import { COOKIE_NAME, exchangeCode } from '@/lib/laravel-auth';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

const MAX_TTL_SECONDS = 86_400;

function cookieTtl(expiresAt: number): number {
    return Math.max(1, Math.min(Math.trunc(expiresAt - Date.now() / 1000), MAX_TTL_SECONDS));
}

// Only a short-lived, single-use code is accepted here. Grant tokens in the URL are
// rejected outright: Laravel consumes them at the POST handoff, never at the app.
export async function GET(request: NextRequest) {
    const code = request.nextUrl.searchParams.get('code');
    const session = code ? await exchangeCode(code) : null;
    if (!session) {
        return NextResponse.redirect(new URL('/', request.url));
    }

    const response = NextResponse.redirect(new URL('/', request.url));
    response.cookies.set(COOKIE_NAME, session.token, {
        httpOnly: true,
        secure: process.env.NODE_ENV === 'production',
        sameSite: 'lax',
        path: '/',
        maxAge: cookieTtl(session.expiresAt),
    });
    return response;
}