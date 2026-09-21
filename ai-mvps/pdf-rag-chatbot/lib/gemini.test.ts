import { describe, expect, it, vi } from 'vitest';

describe('Gemini client configuration', () => {
  it('does not fail to import when GEMINI_API_KEY is absent', async () => {
    vi.stubEnv('GEMINI_API_KEY', '');
    vi.resetModules();

    await expect(import('./gemini')).resolves.toBeDefined();
  });
});
