import { NextRequest, NextResponse } from 'next/server';
import { chunkPages } from '@/lib/rag';
import { embedText } from '@/lib/gemini';
import { extractTextFromPDF, hasPdfMagicBytes, MAX_PAGES, MAX_TEXT_LENGTH, sanitizeText } from '@/lib/pdf-processor';
import { ownerKeyFrom, saveDocument } from '@/lib/storage';
import { RateLimiter } from '@/lib/rate-limit';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';
const MAX_FILE_SIZE = Number(process.env.MAX_FILE_SIZE ?? 10 * 1024 * 1024);
const genericError = { error: 'Unable to process upload' };
// Process-local per-IP sliding window. Best-effort abuse protection only —
// the session check below is the security boundary. Shared-store limiting is
// the multi-instance follow-up; see docs/portfolio-redesign-prd.md item 5.
const uploadLimiter = new RateLimiter(Number(process.env.UPLOAD_RATE_LIMIT ?? 10), 10 * 60_000);

function clientKey(request: NextRequest): string {
  const forwarded = request.headers.get('x-forwarded-for');
  if (forwarded) return forwarded.split(',')[0].trim();
  return request.headers.get('x-real-ip') ?? 'unknown';
}

export async function POST(request: NextRequest) {
  // Correlation ID only — never log document content (PRD item 3).
  const requestId = crypto.randomUUID();
  const log = (category: string) => console.error(`[UPLOAD ${requestId}] ${category}`);
  const ownerKey = await ownerKeyFrom(request);
  if (!ownerKey) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  if (!uploadLimiter.check(`${clientKey(request)}:/api/upload`).allowed) {
    log('Rate limit exceeded');
    return NextResponse.json({ error: 'Too many requests. Please try again later.' }, { status: 429 });
  }
  try {
    const file = (await request.formData()).get('file');
    if (!(file instanceof File) || file.size === 0 || file.size > MAX_FILE_SIZE) {
      log('Invalid file object or size');
      return NextResponse.json(genericError, { status: 400 });
    }
    const buffer = Buffer.from(await file.arrayBuffer());
    if (!hasPdfMagicBytes(buffer)) {
      log('Missing PDF magic bytes');
      return NextResponse.json(genericError, { status: 400 });
    }
    const pages = await extractTextFromPDF(buffer);
    if (pages.length > MAX_PAGES) {
      log('Page count exceeded MAX_PAGES');
      return NextResponse.json(genericError, { status: 400 });
    }
    const cleanPages = pages.map(page => ({ ...page, text: sanitizeText(page.text) }));
    if (cleanPages.reduce((n, page) => n + page.text.length, 0) > MAX_TEXT_LENGTH) {
      log('Text length exceeded MAX_TEXT_LENGTH');
      return NextResponse.json(genericError, { status: 400 });
    }
    const chunks = chunkPages(cleanPages);
    if (!chunks.length) {
      log('No chunks generated from pages');
      return NextResponse.json(genericError, { status: 400 });
    }
    for (const chunk of chunks) {
      chunk.embedding = await embedText(chunk.text);
    }
    const document = await saveDocument({
      id: crypto.randomUUID(),
      filename: file.name.replace(/[^a-zA-Z0-9._-]/g, '_'),
      chunks,
      uploadedAt: new Date().toISOString()
    }, ownerKey);
    return NextResponse.json({
      success: true,
      document: {
        id: document.id,
        filename: document.filename,
        chunkCount: chunks.length,
        uploadedAt: document.uploadedAt
      }
    });
  } catch (error) {
    // Safe category only: error message, never the uploaded document content.
    log(`Request failed: ${error instanceof Error ? error.message : 'unknown'}`);
    return NextResponse.json(genericError, { status: 500 });
  }
}
