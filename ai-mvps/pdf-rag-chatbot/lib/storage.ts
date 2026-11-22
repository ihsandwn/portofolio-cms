import { createHash } from 'node:crypto';
import { query } from './db';
import { createNeonDocumentStore } from './neon-storage';
import type { StoredDocument } from './neon-storage';
import { COOKIE_NAME, verifySession } from './laravel-auth';

const store = createNeonDocumentStore(query);
export type { StoredDocument } from './neon-storage';

/** Bind document access to its original session without storing raw cookie values. */
export async function ownerKeyFrom(request: { cookies: { get(name: string): { value: string } | undefined } }): Promise<string | null> {
  const token = request.cookies.get(COOKIE_NAME)?.value;
  const session = token ? await verifySession(token) : null;
  return session ? createHash('sha256').update(token!).digest('hex') : null;
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