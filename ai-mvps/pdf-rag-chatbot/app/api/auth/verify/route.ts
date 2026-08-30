import { NextRequest, NextResponse } from 'next/server';
import { z } from 'zod';
import { RateLimiter } from '@/lib/rate-limit';
import { verifyLaravelAccessToken } from '@/lib/laravel-auth';

const verifier = new RateLimiter(Number(process.env.VERIFY_RATE_LIMIT ?? 10), 60_000);
const schema = z.object({ token: z.string().min(1) });

export async function POST(request: NextRequest) {
  try {
    const ip = request.headers.get('x-forwarded-for')?.split(',')[0].trim() ?? 'unknown';
    const check = verifier.check(ip, Date.now());
    if (!check.allowed) return NextResponse.json({ error: 'Rate limit exceeded' }, { status: 429 });
    const parsed = schema.safeParse(await request.json());
    if (!parsed.success) return NextResponse.json({ error: 'Invalid token' }, { status: 400 });
    const ok = await verifyLaravelAccessToken(parsed.data.token);
    return NextResponse.json({ valid: ok }, { status: ok ? 200 : 401 });
  } catch { return NextResponse.json({ error: 'Something went wrong' }, { status: 500 }); }
}