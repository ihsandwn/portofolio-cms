import assert from 'node:assert/strict';
import test from 'node:test';
import { analyzeRequestSchema, sentimentResultSchema, languageSchema } from '../lib/schemas.ts';
import { createRateLimiter } from '../lib/rate-limit.ts';
import { isAllowedOrigin } from '../lib/origin.ts';
import { accessTokenSchema, isExpectedCallbackRedirect } from '../lib/auth.ts';
import { DEFAULT_GEMINI_MODEL } from '../lib/gemini-config.ts';

test('request schema enforces language and text limits', () => {
    assert.equal(analyzeRequestSchema.safeParse({ text: 'x', language: 'fr' }).success, false);
    assert.equal(analyzeRequestSchema.safeParse({ text: 'x'.repeat(5001), language: 'en' }).success, false);
    assert.equal(analyzeRequestSchema.safeParse({ text: 'Valid text', language: 'id' }).success, true);
});

test('AI response schema rejects out-of-range and extra data', () => {
    const result = {
        sentiment: 'Positive',
        confidence: 101,
        emotions: { joy: 70, sadness: 10, anger: 5, fear: 5, surprise: 10 },
        explanation: 'Good',
        injected: true,
    };

    assert.equal(sentimentResultSchema.safeParse(result).success, false);
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

test('language schema only accepts en or id', () => {
    assert.equal(languageSchema.safeParse('en').success, true);
    assert.equal(languageSchema.safeParse('id').success, true);
    assert.equal(languageSchema.safeParse('fr').success, false);
});

test('origin validation requires exact request origin', () => {
    assert.equal(isAllowedOrigin('https://example.com', 'https://example.com'), true);
    assert.equal(isAllowedOrigin('https://evil.example', 'https://example.com'), false);
    assert.equal(isAllowedOrigin(null, 'https://example.com'), false);
});

test('access token requires 64 alphanumeric characters', () => {
    assert.equal(accessTokenSchema.safeParse('a'.repeat(64)).success, true);
    assert.equal(accessTokenSchema.safeParse('short').success, false);
    assert.equal(accessTokenSchema.safeParse(`${'a'.repeat(63)}-`).success, false);
});

test('accepts only same-origin callback redirects for the same token', () => {
    const token = 'a'.repeat(64);
    assert.equal(isExpectedCallbackRedirect(`https://sentiment.example/auth/callback?token=${token}`, 'https://cms.test', 'https://sentiment.example', token), true);
    assert.equal(isExpectedCallbackRedirect(`https://image.example/auth/callback?token=${token}`, 'https://cms.test', 'https://sentiment.example', token), false);
    assert.equal(isExpectedCallbackRedirect('https://sentiment.example/auth/callback?token=other', 'https://cms.test', 'https://sentiment.example', token), false);
});

test('uses Gemini 2.5 Flash as default model', () => {
    assert.equal(DEFAULT_GEMINI_MODEL, 'gemini-2.5-flash');
});