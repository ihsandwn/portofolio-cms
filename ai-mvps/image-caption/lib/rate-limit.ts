import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';

const API_HANDLERS = new Map([
  ['/api/caption', { limit: 30, windowMs: 60_000 }],
  ['/api/health', { limit: 60, windowMs: 60_000 }],
]);

const buckets = new Map<string, { timestamps: number[] }>();

export async function withRateLimit(request: NextRequest, next: () => NextResponse | void | Promise<NextResponse | void>): Promise<NextResponse | void> {
  const handler = API_HANDLERS.get(request.nextUrl.pathname);
  if (!handler) return next();

  const ip = request.headers.get('x-forwarded-for')?.split(',')[0]?.trim() || 'unknown';
  const key = `${ip}:${request.nextUrl.pathname}`;
  const now = Date.now();
  let bucket = buckets.get(key);
  if (!bucket || bucket.timestamps[0] < now - handler.windowMs) {
    bucket = { timestamps: [] };
  }
  bucket.timestamps = bucket.timestamps.filter(t => t > now - handler.windowMs);
  bucket.timestamps.push(now);
  buckets.set(key, bucket);

  if (bucket.timestamps.length > handler.limit) {
    return NextResponse.json({ error: 'Too many requests. Please try again later.' }, { status: 429 });
  }
  return next();
}