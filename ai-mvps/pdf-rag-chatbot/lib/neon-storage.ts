import type { Chunk, ChunkWithScore } from './rag';
import { MAX_RETRIEVED_CHUNKS, MIN_SCORE } from './rag';

export interface StoredDocument {
  id: string;
  filename: string;
  chunks: Chunk[];
  uploadedAt: string;
}

export type Query = (sql: string, values?: unknown[]) => Promise<{ rows: Record<string, unknown>[] }>;

function vectorLiteral(values: number[]): string {
  if (!values.length || values.some((value) => !Number.isFinite(value))) throw new Error('Invalid embedding');
  return `[${values.join(',')}]`;
}

export function createNeonDocumentStore(query: Query, now = () => new Date()) {
  return {
    async save(document: StoredDocument, ownerKey: string, ttlMs: number): Promise<StoredDocument> {
      if (!Number.isFinite(ttlMs) || ttlMs <= 0) throw new Error('Invalid document TTL');
      if (!document.chunks.length) throw new Error('Empty document');
      const chunks = document.chunks.map((chunk) => {
        if (!chunk.embedding) throw new Error('Missing chunk embedding');
        return { idx: chunk.index, page: chunk.page, text: chunk.text, embedding: vectorLiteral(chunk.embedding) };
      });
      // Single statement is atomic: all document rows persist, or none do.
      await query(
        `WITH doc AS (
           INSERT INTO documents (id, filename, owner_key, uploaded_at, expires_at)
           VALUES ($1, $2, $3, $4, $5) RETURNING id)
         INSERT INTO document_chunks (document_id, chunk_index, page, content, embedding)
         SELECT doc.id, c.idx, c.page, c.text, c.embedding::vector
         FROM doc, jsonb_to_recordset($6::jsonb) AS c(idx int, page int, text text, embedding text)`,
        [
          document.id,
          document.filename,
          ownerKey,
          document.uploadedAt,
          new Date(now().getTime() + ttlMs).toISOString(),
          JSON.stringify(chunks),
        ]
      );
      return document;
    },

    async exists(documentId: string, ownerKey: string): Promise<boolean> {
      const { rows } = await query(
        'SELECT 1 FROM documents WHERE id = $1 AND owner_key = $2 AND expires_at > now()',
        [documentId, ownerKey]
      );
      return rows.length > 0;
    },

    async retrieve(documentId: string, ownerKey: string, questionEmbedding: number[]): Promise<ChunkWithScore[]> {
      const { rows } = await query(
        `SELECT c.chunk_index, c.page, c.content,
                1 - (c.embedding <=> $1::vector) AS score
         FROM document_chunks c JOIN documents d ON d.id = c.document_id
         WHERE d.id = $2 AND d.owner_key = $3 AND d.expires_at > now()
         ORDER BY c.embedding <=> $1::vector
         LIMIT $4`,
        [vectorLiteral(questionEmbedding), documentId, ownerKey, MAX_RETRIEVED_CHUNKS]
      );
      return rows.map((row) => ({
        index: Number(row.chunk_index),
        page: Number(row.page),
        text: String(row.content),
        score: Number(row.score),
      })).filter((chunk) => chunk.score >= MIN_SCORE);
    },

    async count(): Promise<number> {
      const { rows } = await query('SELECT count(*)::int AS n FROM documents WHERE expires_at > now()');
      return Number(rows[0]?.n ?? 0);
    },

    async cleanup(): Promise<number> {
      const { rows } = await query('DELETE FROM documents WHERE expires_at < now() RETURNING id');
      return rows.length;
    },
  };
}
