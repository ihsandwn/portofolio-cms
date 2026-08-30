import { z } from 'zod';

const ValidationResponseSchema = z.object({ valid: z.literal(true) }).passthrough();

export async function validateToken(token: string): Promise<void> {
  const baseUrl = process.env.LARAVEL_API_URL || process.env.NEXT_PUBLIC_LARAVEL_API_URL;
  if (!baseUrl) throw new Error('Laravel API is not configured');
  const url = process.env.LARAVEL_TOKEN_VERIFY_URL || `${baseUrl.replace(/\/$/, '')}/api/validate-token`;
  const response = await fetch(url, {
    headers: { Accept: 'application/json', Authorization: `Bearer ${token}` },
    cache: 'no-store',
    signal: AbortSignal.timeout(5000),
  });
  if (!response.ok) throw new Error('Token rejected');
  ValidationResponseSchema.parse(await response.json());
}
