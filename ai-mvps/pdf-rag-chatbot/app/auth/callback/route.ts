import { NextRequest, NextResponse } from 'next/server';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

// Laravel grants a 10-minute access token, but the browser session here is what decides
// how long the UI stays reachable. 600s meant the page bounced back to /ai-lab mid-use.
const DEFAULT_TTL_SECONDS = 3600;
const MAX_TTL_SECONDS = 86_400;

function sessionTtl(): number {
    const raw = Number(process.env.MVP_SESSION_TTL_SECONDS);
    if (!Number.isFinite(raw) || raw <= 0) return DEFAULT_TTL_SECONDS;
    return Math.min(Math.trunc(raw), MAX_TTL_SECONDS);
}

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
        path: '/',
        maxAge: sessionTtl(),
    });

    return response;
}
