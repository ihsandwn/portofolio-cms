import { NextRequest, NextResponse } from 'next/server';
import { screenResume } from '@/lib/gemini-hr';
import { parseResume } from '@/lib/resume-parser';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB

export async function POST(request: NextRequest) {
    console.log('[SCREEN] Request received');

    try {
        const formData = await request.formData();
        const file = formData.get('file') as File;
        const jobDescription = formData.get('jobDescription') as string;
        const languageParam = (formData.get('language') as string) || 'en';
        const language: 'en' | 'id' = languageParam === 'id' ? 'id' : 'en';

        if (!file) {
            return NextResponse.json({ error: 'No resume provided' }, { status: 400 });
        }

        if (!jobDescription || jobDescription.trim().length === 0) {
            return NextResponse.json({ error: 'Job description is required' }, { status: 400 });
        }

        // Validate file type
        if (file.type !== 'application/pdf') {
            return NextResponse.json({ error: 'Only PDF resumes are allowed' }, { status: 400 });
        }

        // Validate file size
        if (file.size > MAX_FILE_SIZE) {
            return NextResponse.json({ error: 'Resume too large (max 10MB)' }, { status: 400 });
        }

        console.log('[SCREEN] Processing resume:', file.name, 'Language:', language);

        // Parse PDF
        const arrayBuffer = await file.arrayBuffer();
        const buffer = Buffer.from(arrayBuffer);
        const resumeText = await parseResume(buffer);

        if (!resumeText || resumeText.length < 50) {
            return NextResponse.json({ error: 'Resume appears to be empty or unreadable' }, { status: 400 });
        }

        console.log('[SCREEN] Resume parsed, screening with AI...');

        // Screen with AI
        const screening = await screenResume(resumeText, jobDescription, language);
        console.log('[SCREEN] Screening complete');

        return NextResponse.json({
            success: true,
            filename: file.name,
            ...screening,
            screenedAt: new Date().toISOString(),
        });
    } catch (error) {
        console.error('[SCREEN] Error:', error);
        return NextResponse.json(
            { error: error instanceof Error ? error.message : 'Screening failed' },
            { status: 500 }
        );
    }
}
