export interface PageText {
  page: number;
  text: string;
}

export interface Chunk {
  index: number;
  page: number;
  text: string;
  embedding?: number[];
}

export interface ChunkWithScore extends Chunk {
  score: number;
}

const MAX_CHUNK_CHARS = 1500;
const OVERLAP_CHARS = 150;
export const MAX_RETRIEVED_CHUNKS = 5;
export const MIN_SCORE = 0.2;

export function chunkPages(pages: PageText[], max = MAX_CHUNK_CHARS, overlap = OVERLAP_CHARS): Chunk[] {
  const chunks: Chunk[] = [];
  let index = 1;

  for (const page of pages) {
    const text = page.text.trim();
    if (!text) continue;

    let start = 0;
    while (start < text.length) {
      let end = Math.min(text.length, start + max);
      const cut = text.lastIndexOf(' ', end);
      if (cut > start + max * 0.6) end = cut;
      chunks.push({ index, page: page.page, text: text.slice(start, end).trim() });
      index += 1;
      if (end >= text.length) break;
      start = Math.max(start + 1, end - overlap);
    }
  }

  return chunks;
}

export function cosineSimilarity(a: number[], b: number[]): number {
  if (a.length !== b.length) return 0;
  let dot = 0;
  let normA = 0;
  let normB = 0;
  for (let i = 0; i < a.length; i += 1) {
    dot += a[i] * b[i];
    normA += a[i] * a[i];
    normB += b[i] * b[i];
  }
  if (normA === 0 || normB === 0) return 0;
  return dot / (Math.sqrt(normA) * Math.sqrt(normB));
}

export function retrieveChunks(
  chunks: Chunk[],
  questionEmbedding: number[],
  limit = MAX_RETRIEVED_CHUNKS
): ChunkWithScore[] {
  return chunks
    .map((chunk) => {
      const embedding = chunk.embedding ?? [];
      return { ...chunk, score: cosineSimilarity(questionEmbedding, embedding) };
    })
    .filter((chunk) => chunk.score >= MIN_SCORE)
    .sort((a, b) => b.score - a.score)
    .slice(0, limit);
}

export function formatCitations(chunks: ChunkWithScore[]): string {
  const seen = new Set<string>();
  const pages: string[] = [];
  for (const chunk of chunks) {
    const key = `p.${chunk.page}`;
    if (!seen.has(key)) {
      seen.add(key);
      pages.push(key);
    }
  }
  return pages.join(', ');
}

export function truncateToWords(text: string, maxWords: number): string {
  const words = text.split(/\s+/);
  if (words.length <= maxWords) return text;
  return words.slice(0, maxWords).join(' ') + '...';
}