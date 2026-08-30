import { z } from 'zod';

export const accessTokenSchema = z.string().regex(/^[A-Za-z0-9]{64}$/);

function laravelBaseUrl(): string | null {
    const value = process.env.LARAVEL_API_URL || process.env.NEXT_PUBLIC_LARAVEL_API_URL;
    if (!value) return null;

    try {
        const url = new URL(value);
        if (!['http:', 'https:'].includes(url.protocol)) return null;
        return url.origin;
    } catch {
        return null;
    }
}

export async function validateAccessToken(token: string): Promise<boolean> {
    if (!accessTokenSchema.safeParse(token).success) return false;

    const baseUrl = laravelBaseUrl();
    if (!baseUrl) return false;

    try {
        const response = await fetch(`${baseUrl}/ai-lab/auth/${encodeURIComponent(token)}`, {
            method: 'GET',
            redirect: 'manual',
            cache: 'no-store',
            signal: AbortSignal.timeout(5000),
        });

        if (response.status < 300 || response.status >= 400) return false;

        const location = response.headers.get('location');
        if (!location) return false;

        const redirectUrl = new URL(location, baseUrl);
        return redirectUrl.pathname === '/auth/callback'
            && redirectUrl.searchParams.get('token') === token;
    } catch {
        return false;
    }
}

export function getClientIp(headers: Headers): string {
    return headers.get('x-forwarded-for')?.split(',')[0]?.trim()
        || headers.get('x-real-ip')?.trim()
        || 'unknown';
}
