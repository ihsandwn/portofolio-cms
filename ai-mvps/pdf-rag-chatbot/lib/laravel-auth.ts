export async function verifyLaravelAccessToken(token: string): Promise<boolean> {
  const endpoint = process.env.LARAVEL_TOKEN_VERIFY_URL;
  if (!endpoint) return false;

  try {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), Number(process.env.VERIFY_TIMEOUT_MS ?? 5_000));
    try {
      const response = await fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ token }),
        signal: controller.signal,
      });
      return response.ok;
    } finally {
      clearTimeout(timeout);
    }
  } catch {
    return false;
  }
}
