import assert from 'node:assert/strict';
import test from 'node:test';
import { requestFieldsSchema, screeningResultSchema } from '../lib/schemas.ts';
import { hasPdfMagicBytes } from '../lib/resume-parser.ts';
import { createRateLimiter } from '../lib/rate-limit.ts';
import { AUDIENCE, verifySession } from '../lib/auth.ts';
import { readFileSync } from 'node:fs';

const readUi = (path: string) => readFileSync(new URL(path, import.meta.url), 'utf8');

test('public UI keeps Indonesian results and data handling guidance visible', () => {
    const page = readUi('../app/page.tsx');
    const results = readUi('../components/ScreeningResults.tsx');

    assert.match(page, /Isi resume dan deskripsi pekerjaan dikirim/);
    assert.match(results, /Skor keseluruhan/);
    assert.match(results, /Keterampilan yang sesuai/);
});

test('request schema enforces language and text limits', () => {
    assert.equal(requestFieldsSchema.safeParse({ jobDescription: 'x', language: 'fr' }).success, false);
    assert.equal(requestFieldsSchema.safeParse({ jobDescription: 'x'.repeat(20_001), language: 'en' }).success, false);
    assert.equal(requestFieldsSchema.safeParse({ jobDescription: 'Valid role', language: 'id' }).success, true);
});

test('AI response schema rejects out-of-range and extra data', () => {
    const result = {
        overallScore: 101,
        recommendation: 'Recommended',
        matchedSkills: [],
        missingSkills: [],
        experience: { years: 2, relevance: 'High' },
        education: { level: 'Bachelor', relevance: 'High' },
        strengths: [],
        concerns: [],
        summary: 'Fit',
        injected: true,
    };

    assert.equal(screeningResultSchema.safeParse(result).success, false);
});

test('PDF validation checks file signature', () => {
    assert.equal(hasPdfMagicBytes(Buffer.from('%PDF-1.7')), true);
    assert.equal(hasPdfMagicBytes(Buffer.from('not a pdf')), false);
});

test('rate limiter isolates IP and token combination', () => {
    let now = 1_000;
    const limiter = createRateLimiter({ limit: 2, windowMs: 60_000, now: () => now });

    assert.equal(limiter.consume('1.2.3.4:token-a').allowed, true);
    assert.equal(limiter.consume('1.2.3.4:token-a').allowed, true);
    assert.equal(limiter.consume('1.2.3.4:token-a').allowed, false);
    assert.equal(limiter.consume('1.2.3.4:token-b').allowed, true);
    now += 60_001;
    assert.equal(limiter.consume('1.2.3.4:token-a').allowed, true);
});

test('session verification fails closed without server config', async () => {
    const url = process.env.LARAVEL_API_URL;
    const secret = process.env.MVP_APP_SECRET;
    delete process.env.LARAVEL_API_URL;
    delete process.env.NEXT_PUBLIC_LARAVEL_API_URL;
    delete process.env.MVP_APP_SECRET;
    try {
        assert.equal(await verifySession('a'.repeat(64)), null);
    } finally {
        if (url) process.env.LARAVEL_API_URL = url;
        if (secret) process.env.MVP_APP_SECRET = secret;
    }
});

test('Laravel is the authority: rejected, unreachable and malformed answers deny access', async () => {
    const original = globalThis.fetch;
    process.env.LARAVEL_API_URL = 'https://laravel.test';
    process.env.MVP_APP_SECRET = 's'.repeat(40);
    const token = 'a'.repeat(64);
    try {
        globalThis.fetch = (async () => new Response(JSON.stringify({ valid: false }), { status: 200 })) as unknown as typeof fetch;
        assert.equal(await verifySession(token), null);

        globalThis.fetch = (async () => { throw new Error('down'); }) as unknown as typeof fetch;
        assert.equal(await verifySession(token), null);

        let sent: Record<string, unknown> = {};
        globalThis.fetch = (async (_url: string, init?: RequestInit) => {
            sent = JSON.parse(String(init?.body));
            return new Response(JSON.stringify({ valid: true, expires_at: Math.floor(Date.now() / 1000) + 600 }), { status: 200 });
        }) as unknown as typeof fetch;
        const session = await verifySession(token);
        assert.equal(typeof session?.expiresAt, 'number');
        assert.equal(sent.audience, AUDIENCE);
    } finally {
        globalThis.fetch = original;
    }
});
