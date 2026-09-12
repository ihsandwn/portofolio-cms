export async function verifyLaravelAccessToken(token: string): Promise<boolean> {
  return typeof token === 'string' && token.length > 0;
}
