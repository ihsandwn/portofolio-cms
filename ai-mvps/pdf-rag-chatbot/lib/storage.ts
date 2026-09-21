import type { Chunk } from './rag';

export interface StoredDocument {
  id: string;
  filename: string;
  chunks: Chunk[];
  uploadedAt: string;
}

export class DocumentStore {
  private documents = new Map<string, StoredDocument>();

  constructor(private maxDocuments = 20, private ttlMs = 30 * 60 * 1000) {}

  save(document: StoredDocument): StoredDocument {
    this.prune();
    if (this.documents.size >= this.maxDocuments) {
      const oldest = this.documents.keys().next().value;
      if (oldest) this.documents.delete(oldest);
    }
    this.documents.set(document.id, document);
    return document;
  }

  get(id: string): StoredDocument | undefined {
    this.prune();
    return this.documents.get(id);
  }

  delete(id: string): boolean {
    return this.documents.delete(id);
  }

  count(): number {
    this.prune();
    return this.documents.size;
  }

  private prune(): void {
    const expiry = Date.now() - this.ttlMs;
    for (const [id, document] of this.documents) {
      const uploaded = Date.parse(document.uploadedAt);
      if (!Number.isNaN(uploaded) && uploaded < expiry) this.documents.delete(id);
    }
  }
}

// Each route handler is bundled separately, so a plain module-level instance gives
// /api/upload and /api/chat two different Maps: the upload succeeds and the chat then
// reports "Document unavailable". Pinning the store to globalThis keeps one instance
// per server process, and also survives dev HMR module reloads.
const STORE_KEY: unique symbol = Symbol.for('pdf-rag-chatbot.document-store');
type StoreGlobal = typeof globalThis & { [STORE_KEY]?: DocumentStore };

function getStore(): DocumentStore {
  const scope = globalThis as StoreGlobal;
  scope[STORE_KEY] ??= new DocumentStore(
    Number(process.env.MAX_STORED_DOCUMENTS ?? 20),
    Number(process.env.DOCUMENT_TTL_MS ?? 1_800_000)
  );
  return scope[STORE_KEY];
}

export const saveDocument = (document: StoredDocument) => getStore().save(document);
export const getDocument = (id: string) => getStore().get(id);
export const deleteDocument = (id: string) => getStore().delete(id);
export const documentCount = () => getStore().count();