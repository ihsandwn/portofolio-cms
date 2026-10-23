import { afterEach, describe, expect, it, vi } from 'vitest';

describe('Gemini embedding model selection', () => {
  afterEach(() => {
    vi.unstubAllEnvs();
    vi.restoreAllMocks();
    vi.resetModules();
  });

  it('uses the configured embedding model instead of the retired default', async () => {
    vi.stubEnv('GEMINI_API_KEY', 'test-key');
    vi.stubEnv('EMBEDDING_MODEL', 'gemini-embedding-001');
    vi.resetModules();

    const embedContent = vi.fn().mockResolvedValue({ embedding: { values: [0.1] } });
    const getGenerativeModel = vi.fn().mockReturnValue({ embedContent });
    vi.doMock('@google/generative-ai', () => ({
      GoogleGenerativeAI: vi.fn(function GoogleGenerativeAI() {
        return { getGenerativeModel };
      }),
    }));

    const { embedText } = await import('./gemini');
    await expect(embedText('page text')).resolves.toEqual([0.1]);
    expect(getGenerativeModel).toHaveBeenCalledWith({ model: 'gemini-embedding-001' });
  });
});
