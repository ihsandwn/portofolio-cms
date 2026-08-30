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

const store = new DocumentStore(
  Number(process.env.MAX_STORED_DOCUMENTS ?? 20),
  Number(process.env.DOCUMENT_TTL_MS ?? 1_800_000)
);

export const saveDocument = (document: StoredDocument) => store.save(document);
export const getDocument = (id: string) => store.get(id);
export const deleteDocument = (id: string) => store.delete(id);
export const documentCount = () => store.count();