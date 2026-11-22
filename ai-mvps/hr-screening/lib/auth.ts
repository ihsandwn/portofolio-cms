import { z } from 'zod';

export const accessTokenSchema = z.string().regex(/^[A-Za-z0-9]{64}$/);

export const AUDIENCE = 'hr-screening';
export const COOKIE_NAME = 'mvp-access-hr-screening';

export function getClientIp(headers: Headers): string {
    return headers.get('x-forwarded-for')?.split(',')[0]?.trim()
        || headers.get('x-real-ip')?.trim()
        || 'unknown';
}

function config(): { base: string; secret: string } | null {
    const base = process.env.LARAVEL_API_URL ?? process.env.NEXT_PUBLIC_LARAVEL_API_URL;
    const secret = process.env.MVP_APP_SECRET;
    if (!base || !secret || secret.length < 32) return null;
    return { base: base.replace(/\/+$/, ''), secret };
}

async function call(path: string, body: Record<string, unknown>): Promise<Record<string, unknown> | null> {
    const cfg = config();
    if (!cfg) return null;
    try {
        const response = await fetch(`${cfg.base}/api/mvp/auth/${path}`, {
            method: 'POST',
            headers: { 'content-type': 'application/json', authorization: `Bearer ${cfg.secret}` },
            body: JSON.stringify({ ...body, audience: AUDIENCE }),
            signal: AbortSignal.timeout(3000),
            cache: 'no-store',
        });
        if (!response.ok) return null;
        return await response.json() as Record<string, unknown>;
    } catch {
        return null;
    }
}

/** Redeem a single-use handoff code for an opaque session token. */
export async function exchangeCode(code: string): Promise<{ token: string; expiresAt: number } | null> {
    if (!/^[A-Za-z0-9]{48}$/.test(code)) return null;
    const data = await call('exchange', { code });
    if (!data || !accessTokenSchema.safeParse(data.token).success || typeof data.expires_at !== 'number') return null;
    return { token: data.token as string, expiresAt: data.expires_at };
}

/** Ask Laravel whether this opaque token is a live session for this app. */
export async function verifySession(token: string | undefined): Promise<{ expiresAt: number } | null> {
    if (!token || !accessTokenSchema.safeParse(token).success) return null;
    const data = await call('verify', { token });
    if (!data || data.valid !== true || typeof data.expires_at !== 'number') return null;
    return { expiresAt: data.expires_at };
}
