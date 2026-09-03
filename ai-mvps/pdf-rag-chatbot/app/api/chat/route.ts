import { NextRequest, NextResponse } from 'next/server';
import { z } from 'zod';
import { embedText, streamGroundedAnswer } from '@/lib/gemini';
import { formatCitations, retrieveChunks, truncateToWords } from '@/lib/rag';
import { RateLimiter } from '@/lib/rate-limit';
import { getDocument } from '@/lib/storage';
import { validateAccessToken } from '@/lib/laravel-auth';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';
const schema = z.object({ documentId: z.string().uuid(), question: z.string().trim().min(1).max(2_000), language: z.enum(['en', 'id']).default('en') });
const limiter = new RateLimiter(Number(process.env.CHAT_RATE_LIMIT ?? 60), 60_000);

function clientKey(request: NextRequest): string {
  const forwarded = request.headers.get('x-forwarded-for');
  if (forwarded) return forwarded.split(',')[0].trim();
  return request.headers.get('x-real-ip') ?? 'unknown';
}

export async function POST(request: NextRequest) {
  const token = request.cookies.get('mvp-access-pdf-rag')?.value;
  if (!token || !(await validateAccessToken(token, request.nextUrl.origin))) {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  }

  if (!limiter.check(`${clientKey(request)}:${token}`).allowed) {
    return NextResponse.json({ error: 'Rate limit exceeded' }, { status: 429 });
  }
  try {
    const input = schema.parse(await request.json());
    const document = getDocument(input.documentId);
    if (!document) return NextResponse.json({ error: 'Document unavailable' }, { status: 404 });
    const matches = retrieveChunks(document.chunks, await embedText(input.question));
    if (!matches.length) return new NextResponse('I cannot find that in the document.', { headers: { 'Content-Type': 'text/plain; charset=utf-8' } });
    const context = matches.map(chunk => `[p. ${chunk.page}, chunk ${chunk.index}] ${truncateToWords(chunk.text, 300)}`).join('\n\n');
    const stream = await streamGroundedAnswer(context, input.question, input.language);
    const encoder = new TextEncoder();
    const body = new ReadableStream({ async start(controller) { try { for await (const chunk of stream) controller.enqueue(encoder.encode(chunk.text())); controller.enqueue(encoder.encode(`\n\nSources: ${formatCitations(matches)}`)); controller.close(); } catch { controller.error(new Error('Generation failed')); } } });
    return new NextResponse(body, { headers: { 'Content-Type': 'text/plain; charset=utf-8', 'Cache-Control': 'no-cache, no-transform' } });
  } catch (error) {
    if (error instanceof z.ZodError) return NextResponse.json({ error: 'Invalid request' }, { status: 400 });
    return NextResponse.json({ error: 'Unable to answer request' }, { status: 500 });
  }
}