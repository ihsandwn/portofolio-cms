import { createHash } from 'node:crypto';
import { query } from './db';
import { createNeonDocumentStore } from './neon-storage';
import type { StoredDocument } from './neon-storage';

export type { StoredDocument } from './neon-storage';

const store = createNeonDocumentStore(query);
export const COOKIE_NAME = 'mvp-access-pdf-rag';

/** Bind document access to its original session without storing raw cookie values. */
export function ownerKeyFrom(request: { cookies: { get(name: string): { value: string } | undefined } }): string | null {
  const token = request.cookies.get(COOKIE_NAME)?.value;
  return token ? createHash('sha256').update(token).digest('hex') : null;
}

export async function saveDocument(document: StoredDocument, ownerKey: string) {
  const saved = await store.save(document, ownerKey, Number(process.env.DOCUMENT_TTL_MS ?? 1_800_000));
  // ponytail: expired rows purged opportunistically on upload; add Vercel Cron if traffic is too sparse.
  await store.cleanup().catch(() => undefined);
  return saved;
}
export const documentExists = store.exists;
export const retrieveDocumentChunks = store.retrieve;
export const documentCount = store.count;