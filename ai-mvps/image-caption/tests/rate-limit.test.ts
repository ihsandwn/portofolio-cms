import { describe, expect, it } from 'vitest';
import { NextRequest, NextResponse } from 'next/server';
import { withRateLimit } from '../lib/rate-limit';

const ok = () => NextResponse.json({ ok: true });

function captionRequest(ip?: string) {
  return new NextRequest('http://localhost/api/caption', {
    method: 'POST',
    headers: ip ? { 'x-forwarded-for': ip } : {},
  });
}

describe('withRateLimit for /api/caption', () => {
  it('allows requests within budget and answers 429 past 30 req/min', async () => {
    const ip = `10.9.${Math.floor(Math.random() * 100000)}.1`;
    for (let i = 0; i < 30; i += 1) {
      const result = await withRateLimit(captionRequest(ip), ok);
      expect(result).toBeUndefined();
    }
    const limited = await withRateLimit(captionRequest(ip), ok);
    expect(limited).toBeInstanceOf(NextResponse);
    expect((limited as NextResponse).status).toBe(429);
  });

  it('passes through paths without a configured handler', async () => {
    const request = new NextRequest('http://localhost/api/health', { method: 'GET' });
    const result = await withRateLimit(request, ok);
    expect(result).toBeUndefined();
  });
});
