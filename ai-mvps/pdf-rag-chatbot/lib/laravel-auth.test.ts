import { describe, expect, it } from 'vitest';
import { accessTokenSchema, isExpectedCallbackRedirect } from './laravel-auth';
import { DEFAULT_GEMINI_MODEL } from './gemini-config';

const accessToken = 'a'.repeat(64);

describe('Laravel access contract', () => {
  it('accepts only 64-character alphanumeric tokens', () => {
    expect(accessTokenSchema.safeParse(accessToken).success).toBe(true);
    expect(accessTokenSchema.safeParse('short').success).toBe(false);
    expect(accessTokenSchema.safeParse(`${'a'.repeat(63)}-`).success).toBe(false);
  });

  it('accepts only same-origin callback redirects for the same token', () => {
    expect(isExpectedCallbackRedirect(`https://pdf.example/auth/callback?token=${accessToken}`, 'https://cms.test', 'https://pdf.example', accessToken)).toBe(true);
    expect(isExpectedCallbackRedirect(`https://image.example/auth/callback?token=${accessToken}`, 'https://cms.test', 'https://pdf.example', accessToken)).toBe(false);
    expect(isExpectedCallbackRedirect('https://pdf.example/auth/callback?token=other', 'https://cms.test', 'https://pdf.example', accessToken)).toBe(false);
    expect(isExpectedCallbackRedirect(`https://pdf.example/wrong?token=${accessToken}`, 'https://cms.test', 'https://pdf.example', accessToken)).toBe(false);
    expect(isExpectedCallbackRedirect(null, 'https://cms.test', 'https://pdf.example', accessToken)).toBe(false);
  });

  it('uses Gemini 2.5 Flash as default model', () => {
    expect(DEFAULT_GEMINI_MODEL).toBe('gemini-2.5-flash');
  });
});
