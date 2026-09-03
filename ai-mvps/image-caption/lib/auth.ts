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

export function isExpectedCallbackRedirect(
  location: string | null,
  baseUrl: string,
  expectedOrigin: string,
  token: string
): boolean {
  if (!location) return false;

  try {
    const redirectUrl = new URL(location, baseUrl);
    return redirectUrl.origin === expectedOrigin
      && redirectUrl.pathname === '/auth/callback'
      && redirectUrl.searchParams.get('token') === token;
  } catch {
    return false;
  }
}

export async function validateAccessToken(token: string, expectedOrigin: string): Promise<boolean> {
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

    return response.status >= 300
      && response.status < 400
      && isExpectedCallbackRedirect(response.headers.get('location'), baseUrl, expectedOrigin, token);
  } catch {
    return false;
  }
}
