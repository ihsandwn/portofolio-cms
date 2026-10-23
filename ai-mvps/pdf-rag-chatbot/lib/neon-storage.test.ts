import { afterAll, beforeAll, describe, expect, it } from 'vitest';
import { PGlite } from '@electric-sql/pglite';
import { vector } from '@electric-sql/pglite-pgvector';
import { readFileSync } from 'node:fs';
import { createNeonDocumentStore } from './neon-storage';
import type { StoredDocument } from './neon-storage';
const db = new PGlite({ extensions: { vector } });
const query = (sql: string, values?: unknown[]) => db.query<Record<string, unknown>>(sql, values);
const embedding: number[] = [1, ...Array(3071).fill(0)];
const document = (): StoredDocument => ({
  id: crypto.randomUUID(), filename: 'guide.pdf', uploadedAt: new Date().toISOString(),
  chunks: [{ index: 1, page: 1, text: 'Llamas live in Andes.', embedding }],
});
beforeAll(async () => { await db.exec(readFileSync('db/schema.sql', 'utf8')); }, 30_000);
afterAll(() => db.close());
describe('Neon PostgreSQL + pgvector storage', () => {
  it('persists across independent store instances', async () => {
    const doc = document();
    await createNeonDocumentStore(query).save(doc, 'a', 1_800_000);
    const reader = createNeonDocumentStore(query);
    expect(await reader.exists(doc.id, 'a')).toBe(true);
    expect(await reader.retrieve(doc.id, 'a', embedding)).toEqual([
      { index: 1, page: 1, text: 'Llamas live in Andes.', score: 1 },
    ]);
  });
  it('blocks other owners and expired documents; cleanup cascades', async () => {
    const doc = document(); const store = createNeonDocumentStore(query);
    await store.save(doc, 'a', 1_800_000);
    expect(await store.exists(doc.id, 'b')).toBe(false);
    expect(await store.retrieve(doc.id, 'b', embedding)).toEqual([]);
    await query("UPDATE documents SET expires_at = now() - interval '1 second' WHERE id = $1", [doc.id]);
    expect(await store.exists(doc.id, 'a')).toBe(false);
    expect(await store.retrieve(doc.id, 'a', embedding)).toEqual([]);
    await store.cleanup();
    expect((await query('SELECT 1 FROM document_chunks WHERE document_id = $1', [doc.id])).rows).toEqual([]);
  });
  it('rolls back metadata when a chunk insert fails', async () => {
    const doc = document(); doc.chunks.push({ ...doc.chunks[0] });
    await expect(createNeonDocumentStore(query).save(doc, 'a', 1_800_000)).rejects.toThrow();
    expect((await query('SELECT 1 FROM documents WHERE id = $1', [doc.id])).rows).toEqual([]);
  });
  it('rejects invalid TTL and empty documents', async () => {
    const store = createNeonDocumentStore(query);
    await expect(store.save(document(), 'a', -1)).rejects.toThrow('Invalid document TTL');
    const doc = document(); doc.chunks = [];
    await expect(store.save(doc, 'a', 1000)).rejects.toThrow('Empty document');
  });
  it('rejects invalid embeddings', async () => {
    const doc = document(); doc.chunks[0].embedding = [NaN];
    await expect(createNeonDocumentStore(query).save(doc, 'a', 1_800_000)).rejects.toThrow('Invalid embedding');
  });
  it('ranks cosine matches and drops irrelevant chunks', async () => {
    const doc = document();
    doc.chunks.push({ index: 2, page: 2, text: 'irrelevant', embedding: [0, 1, ...Array(3070).fill(0)] });
    const store = createNeonDocumentStore(query);
    await store.save(doc, 'a', 1_800_000);
    expect((await store.retrieve(doc.id, 'a', embedding)).map(c => c.text)).toEqual(['Llamas live in Andes.']);
    expect(await store.count()).toBeGreaterThan(0);
  });
});

