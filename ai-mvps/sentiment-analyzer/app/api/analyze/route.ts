import { NextRequest, NextResponse } from 'next/server';
import { analyzeSentiment } from '@/lib/gemini';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

export async function POST(request: NextRequest) {
    console.log('[ANALYZE] Request received');

    try {
        const { text, language = 'en' } = await request.json();

        if (!text || text.trim().length === 0) {
            return NextResponse.json({ error: 'Text is required' }, { status: 400 });
        }

        if (text.length > 5000) {
            return NextResponse.json({ error: 'Text too long (max 5000 characters)' }, { status: 400 });
        }

        console.log('[ANALYZE] Analyzing text with language:', language);
        const result = await analyzeSentiment(text, language);
        console.log('[ANALYZE] Analysis complete');

        return NextResponse.json({
            success: true,
            ...result,
            analyzedAt: new Date().toISOString(),
        });
    } catch (error) {
        console.error('[ANALYZE] Error:', error);
        return NextResponse.json(
            { error: error instanceof Error ? error.message : 'Analysis failed' },
            { status: 500 }
        );
    }
}
