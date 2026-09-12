import { z } from 'zod';

export const accessTokenSchema = z.string().regex(/^[A-Za-z0-9]{64}$/);

export function getClientIp(headers: Headers): string {
    return headers.get('x-forwarded-for')?.split(',')[0]?.trim()
        || headers.get('x-real-ip')?.trim()
        || 'unknown';
}
