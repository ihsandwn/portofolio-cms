import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';
import { COOKIE_NAME, verifySession } from './lib/auth';

// Short-lived positive cache so one page load does not fan out into a verify call per
// request. Sessions stay server-authoritative: at most 15s of staleness.
const CACHE_TTL_MS = 15_000;
const CACHE_MAX = 500;
const cache = new Map<string, number>();

async function validSession(token: string | undefined): Promise<boolean> {
    if (!token) return false;
    const cached = cache.get(token);
    if (cached && cached > Date.now()) return true;
    const session = await verifySession(token);
    if (!session) {
        cache.delete(token);
        return false;
    }
    if (cache.size >= CACHE_MAX) cache.clear();
    cache.set(token, Math.min(session.expiresAt * 1000, Date.now() + CACHE_TTL_MS));
    return true;
}

export async function middleware(request: NextRequest) {
    const { pathname } = request.nextUrl;

    if (pathname.startsWith('/auth/callback')) return NextResponse.next();

    const token = request.cookies.get(COOKIE_NAME)?.value;
    if (await validSession(token)) return NextResponse.next();

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
    matcher: ['/((?!_next/static|_next/image|favicon.ico|.*\\.(?:svg|png|jpg|jpeg|gif|webp|ico|css|js|map)$).*)'],
};
