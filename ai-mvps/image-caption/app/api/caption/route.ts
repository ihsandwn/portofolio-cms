import { NextRequest, NextResponse } from 'next/server';
import { generateImageCaption } from '@/lib/gemini-vision';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB

export async function POST(request: NextRequest) {
    console.log('[CAPTION] Request received');

    try {
        const formData = await request.formData();
        const file = formData.get('file') as File;
        const languageParam = (formData.get('language') as string) || 'en';
        const language: 'en' | 'id' = languageParam === 'id' ? 'id' : 'en';

        if (!file) {
            return NextResponse.json({ error: 'No image provided' }, { status: 400 });
        }

        // Validate file type
        if (!file.type.startsWith('image/')) {
            return NextResponse.json({ error: 'Only image files are allowed' }, { status: 400 });
        }

        // Validate file size
        if (file.size > MAX_FILE_SIZE) {
            return NextResponse.json({ error: 'Image too large (max 10MB)' }, { status: 400 });
        }

        console.log('[CAPTION] Processing image:', file.name, 'Language:', language);

        // Convert to base64
        const arrayBuffer = await file.arrayBuffer();
        const base64 = Buffer.from(arrayBuffer).toString('base64');
        const imageData = `data:${file.type};base64,${base64}`;

        // Generate caption with language
        const caption = await generateImageCaption(imageData, language);
        console.log('[CAPTION] Caption generated');

        return NextResponse.json({
            success: true,
            filename: file.name,
            ...caption,
            captionedAt: new Date().toISOString(),
        });
    } catch (error) {
        console.error('[CAPTION] Error:', error);
        return NextResponse.json(
            { error: error instanceof Error ? error.message : 'Caption generation failed' },
            { status: 500 }
        );
    }
}
