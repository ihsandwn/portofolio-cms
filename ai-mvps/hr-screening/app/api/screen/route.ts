import { NextRequest, NextResponse } from 'next/server';
import { screenResume } from '@/lib/gemini-hr';
import { getClientIp, validateAccessToken } from '@/lib/auth';
import { screenRateLimiter } from '@/lib/rate-limit';
import { hasPdfMagicBytes, parseResume } from '@/lib/resume-parser';
import { requestFieldsSchema, screeningResultSchema } from '@/lib/schemas';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

const MAX_FILE_SIZE = 10 * 1024 * 1024;
const MAX_RESUME_TEXT_LENGTH = 50_000;

function errorResponse(error: string, status: number) {
    return NextResponse.json({ error }, { status });
}

export async function POST(request: NextRequest) {
    const token = request.cookies.get('mvp-access-hr-screening')?.value;
    if (!token || !(await validateAccessToken(token))) {
        return errorResponse('Unauthorized', 401);
    }

    const rateLimit = screenRateLimiter.consume(`${getClientIp(request.headers)}:${token}`);
    if (!rateLimit.allowed) {
        return NextResponse.json(
            { error: 'Too many requests. Please try again later.' },
            { status: 429, headers: { 'Retry-After': String(rateLimit.retryAfterSeconds) } }
        );
    }

    try {
        const formData = await request.formData();
        const fields = requestFieldsSchema.safeParse({
            jobDescription: formData.get('jobDescription'),
            language: formData.get('language') ?? 'en',
        });
        const file = formData.get('file');

        if (!fields.success || !(file instanceof File)) {
            return errorResponse('Invalid screening request.', 400);
        }

        if (file.size === 0 || file.size > MAX_FILE_SIZE || file.name.length > 255) {
            return errorResponse('Invalid resume file.', 400);
        }

        const buffer = Buffer.from(await file.arrayBuffer());
        if (!hasPdfMagicBytes(buffer)) {
            return errorResponse('Invalid resume file.', 400);
        }

        const resumeText = (await parseResume(buffer)).trim();
        if (resumeText.length < 50 || resumeText.length > MAX_RESUME_TEXT_LENGTH) {
            return errorResponse('Resume could not be processed.', 400);
        }

        const screening = screeningResultSchema.safeParse(
            await screenResume(resumeText, fields.data.jobDescription, fields.data.language)
        );
        if (!screening.success) {
            console.error('[SCREEN] Invalid AI response', screening.error.flatten());
            return errorResponse('Screening could not be completed.', 502);
        }

        return NextResponse.json({
            success: true,
            filename: file.name,
            ...screening.data,
            screenedAt: new Date().toISOString(),
        });
    } catch (error) {
        console.error('[SCREEN] Request failed', error);
        return errorResponse('Screening could not be completed.', 500);
    }
}
