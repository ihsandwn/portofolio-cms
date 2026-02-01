import { NextRequest, NextResponse } from 'next/server';
import { streamChatWithDocument } from '@/lib/gemini';
import { getDocument } from '@/lib/storage';

// Force Node.js runtime for streaming
export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

export async function POST(request: NextRequest) {
    try {
        const { documentId, question, history = [], language = 'en' } = await request.json();

        if (!documentId || !question) {
            return NextResponse.json({ error: 'documentId and question are required' }, { status: 400 });
        }

        // Get document
        const doc = getDocument(documentId);
        if (!doc) {
            return NextResponse.json({ error: 'Document not found' }, { status: 404 });
        }

        // Create streaming response
        const encoder = new TextEncoder();
        const stream = new ReadableStream({
            async start(controller) {
                try {
                    const chatStream = await streamChatWithDocument(doc.text, question, history, language);

                    for await (const chunk of chatStream) {
                        const text = chunk.text();
                        controller.enqueue(encoder.encode(text));
                    }

                    controller.close();
                } catch (error) {
                    console.error('Streaming error:', error);
                    controller.error(error);
                }
            },
        });

        return new NextResponse(stream, {
            headers: {
                'Content-Type': 'text/plain; charset=utf-8',
                'Transfer-Encoding': 'chunked',
            },
        });
    } catch (error) {
        console.error('Chat error:', error);
        return NextResponse.json(
            { error: error instanceof Error ? error.message : 'Failed to process chat' },
            { status: 500 }
        );
    }
}
