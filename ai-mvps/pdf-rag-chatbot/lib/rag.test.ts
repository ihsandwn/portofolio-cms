import { describe, expect, it } from 'vitest';
import { chunkPages, cosineSimilarity, retrieveChunks } from './rag';
import { hasPdfMagicBytes } from './pdf-processor';
import { DocumentStore } from './storage';
import { RateLimiter } from './rate-limit';

describe('RAG utilities', () => {
  it('chunks pages with stable page and chunk citations', () => {
    const chunks = chunkPages([{ page: 2, text: 'one two three four five six' }], 18, 4);
    expect(chunks.length).toBeGreaterThan(1);
    expect(chunks[0]).toMatchObject({ page: 2, index: 1 });
    expect(chunks[1].text).toContain('four');
  });

  it('computes cosine similarity and retrieves highest score', () => {
    expect(cosineSimilarity([1, 0], [1, 0])).toBe(1);
    const result = retrieveChunks([
      { index: 1, page: 1, text: 'wrong', embedding: [0, 1] },
      { index: 2, page: 2, text: 'right', embedding: [1, 0] },
    ], [1, 0], 1);
    expect(result[0].text).toBe('right');
  });
});

describe('input and resource controls', () => {
  it('requires PDF magic bytes', () => {
    expect(hasPdfMagicBytes(Buffer.from('%PDF-1.7'))).toBe(true);
    expect(hasPdfMagicBytes(Buffer.from('not pdf'))).toBe(false);
  });

  it('evicts oldest document when bounded store fills', () => {
    const store = new DocumentStore(1, 10_000);
    const uploadedAt = new Date().toISOString();
    store.save({ id: 'old', filename: 'old.pdf', chunks: [], uploadedAt });
    store.save({ id: 'new', filename: 'new.pdf', chunks: [], uploadedAt });
    expect(store.get('old')).toBeUndefined();
    expect(store.get('new')?.filename).toBe('new.pdf');
  });

  it('limits requests in fixed window', () => {
    const limiter = new RateLimiter(2, 1_000);
    expect(limiter.check('ip', 0).allowed).toBe(true);
    expect(limiter.check('ip', 1).allowed).toBe(true);
    expect(limiter.check('ip', 2).allowed).toBe(false);
    expect(limiter.check('ip', 1_001).allowed).toBe(true);
  });

  it('stores state across the same key in one window', () => {
    const limiter = new RateLimiter(3, 5_000, () => 500);
    expect(limiter.check('user-a').allowed).toBe(true);
    expect(limiter.check('user-a').allowed).toBe(true);
    expect(limiter.check('user-a').allowed).toBe(true);
    expect(limiter.check('user-a').allowed).toBe(false);
  });
});
