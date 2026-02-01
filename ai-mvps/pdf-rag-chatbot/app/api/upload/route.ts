import { NextRequest, NextResponse } from 'next/server';
import { extractTextFromPDF, sanitizeText } from '@/lib/pdf-processor';
import { saveDocument } from '@/lib/storage';

// Force Node.js runtime (pdf-parse requires Node.js APIs)
export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

const MAX_FILE_SIZE = parseInt(process.env.MAX_FILE_SIZE || '10485760'); // 10MB

export async function POST(request: NextRequest) {
    console.log('[UPLOAD] Request received');

    try {
        console.log('[UPLOAD] Parsing form data...');
        const formData = await request.formData();
        const file = formData.get('file') as File;

        console.log('[UPLOAD] File:', file ? file.name : 'NO FILE');

        if (!file) {
            console.log('[UPLOAD] Error: No file provided');
            return NextResponse.json({ error: 'No file provided' }, { status: 400 });
        }

        // Validate file type
        console.log('[UPLOAD] File type:', file.type);
        if (file.type !== 'application/pdf') {
            console.log('[UPLOAD] Error: Invalid file type');
            return NextResponse.json({ error: 'Only PDF files are allowed' }, { status: 400 });
        }

        // Validate file size
        console.log('[UPLOAD] File size:', file.size, 'bytes');
        if (file.size > MAX_FILE_SIZE) {
            console.log('[UPLOAD] Error: File too large');
            return NextResponse.json(
                { error: `File size must be less than ${MAX_FILE_SIZE / 1024 / 1024}MB` },
                { status: 400 }
            );
        }

        // Convert to buffer
        console.log('[UPLOAD] Converting to buffer...');
        const arrayBuffer = await file.arrayBuffer();
        const buffer = Buffer.from(arrayBuffer);
        console.log('[UPLOAD] Buffer size:', buffer.length);

        // Extract text
        console.log('[UPLOAD] Extracting text from PDF...');
        const rawText = await extractTextFromPDF(buffer);
        console.log('[UPLOAD] Raw text length:', rawText?.length || 0);

        const text = sanitizeText(rawText);
        console.log('[UPLOAD] Sanitized text length:', text?.length || 0);

        if (!text || text.length === 0) {
            console.log('[UPLOAD] Error: No text extracted');
            return NextResponse.json({ error: 'No text could be extracted from PDF' }, { status: 400 });
        }

        // Save document
        console.log('[UPLOAD] Saving document...');
        const id = crypto.randomUUID();
        const doc = saveDocument(id, file.name, text);
        console.log('[UPLOAD] Document saved:', doc.id);

        return NextResponse.json({
            success: true,
            document: {
                id: doc.id,
                filename: doc.filename,
                textLength: doc.text.length,
                uploadedAt: doc.uploadedAt,
            },
        });
    } catch (error) {
        console.error('[UPLOAD] ERROR:', error);
        console.error('[UPLOAD] Error stack:', error instanceof Error ? error.stack : 'No stack trace');
        console.error('[UPLOAD] Error message:', error instanceof Error ? error.message : String(error));

        return NextResponse.json(
            {
                error: error instanceof Error ? error.message : 'Failed to process PDF',
                details: error instanceof Error ? error.stack : String(error)
            },
            { status: 500 }
        );
    }
}
