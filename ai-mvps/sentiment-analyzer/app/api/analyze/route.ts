import { NextRequest, NextResponse } from 'next/server';
import { analyzeSentiment } from '@/lib/gemini';
import { getClientIp, validateAccessToken } from '@/lib/auth';
import { analyzeRateLimiter } from '@/lib/rate-limit';
import { isAllowedOrigin } from '@/lib/origin';
import { analyzeRequestSchema, sentimentResultSchema } from '@/lib/schemas';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

function errorResponse(error: string, status: number) {
    return NextResponse.json({ error }, { status });
}

export async function POST(request: NextRequest) {
    if (!isAllowedOrigin(request.headers.get('origin'), request.nextUrl.origin)) {
        return errorResponse('Forbidden', 403);
    }

    const token = request.cookies.get('mvp-access-sentiment')?.value;
    if (!token || !(await validateAccessToken(token, request.nextUrl.origin))) {
        return errorResponse('Unauthorized', 401);
    }

    const rateLimit = analyzeRateLimiter.consume(`${getClientIp(request.headers)}:${token}`);
    if (!rateLimit.allowed) {
        return NextResponse.json(
            { error: 'Too many requests. Please try again later.' },
            { status: 429, headers: { 'Retry-After': String(rateLimit.retryAfterSeconds) } }
        );
    }

    try {
        const body = await request.json();
        const fields = analyzeRequestSchema.safeParse(body);

        if (!fields.success) {
            return errorResponse('Invalid request.', 400);
        }

        const sentiment = sentimentResultSchema.safeParse(
            await analyzeSentiment(fields.data.text, fields.data.language)
        );

        if (!sentiment.success) {
            console.error('[ANALYZE] Invalid AI response', sentiment.error.flatten());
            return errorResponse('Analysis could not be completed.', 502);
        }

        return NextResponse.json({
            success: true,
            ...sentiment.data,
            analyzedAt: new Date().toISOString(),
        });
    } catch (error) {
        console.error('[ANALYZE] Request failed', error);
        return errorResponse('Analysis could not be completed.', 500);
    }
}
