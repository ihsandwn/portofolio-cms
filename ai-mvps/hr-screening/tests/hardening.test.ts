import assert from 'node:assert/strict';
import test from 'node:test';
import { requestFieldsSchema, screeningResultSchema } from '../lib/schemas.ts';
import { hasPdfMagicBytes } from '../lib/resume-parser.ts';
import { createRateLimiter } from '../lib/rate-limit.ts';

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
