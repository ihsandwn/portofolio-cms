import { NextRequest, NextResponse } from 'next/server';
import { chunkPages } from '@/lib/rag';
import { embedText } from '@/lib/gemini';
import { extractTextFromPDF, hasPdfMagicBytes, MAX_PAGES, MAX_TEXT_LENGTH, sanitizeText } from '@/lib/pdf-processor';
import { saveDocument } from '@/lib/storage';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';
const MAX_FILE_SIZE = Number(process.env.MAX_FILE_SIZE ?? 10 * 1024 * 1024);
const genericError = { error: 'Unable to process upload' };

export async function POST(request: NextRequest) {
  try {
    const file = (await request.formData()).get('file');
    if (!(file instanceof File) || file.size === 0 || file.size > MAX_FILE_SIZE) return NextResponse.json(genericError, { status: 400 });
    const buffer = Buffer.from(await file.arrayBuffer());
    if (!hasPdfMagicBytes(buffer)) return NextResponse.json(genericError, { status: 400 });
    const pages = await extractTextFromPDF(buffer);
    if (pages.length > MAX_PAGES) return NextResponse.json(genericError, { status: 400 });
    const cleanPages = pages.map(page => ({ ...page, text: sanitizeText(page.text) }));
    if (cleanPages.reduce((n, page) => n + page.text.length, 0) > MAX_TEXT_LENGTH) return NextResponse.json(genericError, { status: 400 });
    const chunks = chunkPages(cleanPages);
    if (!chunks.length) return NextResponse.json(genericError, { status: 400 });
    for (const chunk of chunks) chunk.embedding = await embedText(chunk.text);
    const document = saveDocument({ id: crypto.randomUUID(), filename: file.name.replace(/[^a-zA-Z0-9._-]/g, '_'), chunks, uploadedAt: new Date().toISOString() });
    return NextResponse.json({ success: true, document: { id: document.id, filename: document.filename, chunkCount: chunks.length, uploadedAt: document.uploadedAt } });
  } catch { return NextResponse.json(genericError, { status: 500 }); }
}