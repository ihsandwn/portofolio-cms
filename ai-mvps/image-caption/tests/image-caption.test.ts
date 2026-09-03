import { describe, it, expect } from 'vitest';
import { mimeFromExtension, matchesMagic } from '../lib/image-validation';
import { CaptionSchema } from '../lib/schemas';
import { accessTokenSchema, isExpectedCallbackRedirect } from '../lib/auth';
import { DEFAULT_GEMINI_MODEL } from '../lib/gemini-config';

const accessToken = 'a'.repeat(64);

describe('Image Validation & Schemas', () => {
  it('identifies mime from extension', () => {
    expect(mimeFromExtension('test.jpg')).toBe('image/jpeg');
    expect(mimeFromExtension('test.png')).toBe('image/png');
    expect(mimeFromExtension('test.webp')).toBe('image/webp');
    expect(mimeFromExtension('test.gif')).toBe('image/gif');
    expect(mimeFromExtension('test.txt')).toBeNull();
  });

  it('validates magic bytes for JPEG', () => {
    const jpegBytes = new Uint8Array([0xff, 0xd8, 0xff, 0xe0]);
    expect(matchesMagic(jpegBytes, 'image/jpeg')).toBe(true);
    expect(matchesMagic(jpegBytes, 'image/png')).toBe(false);
  });

  it('validates CaptionSchema correctness', () => {
    const validData = {
      title: 'A Mountain Sunset',
      caption: 'Golden sunset over rugged mountain peaks.',
      categories: ['nature', 'sunset'],
      objects: ['mountain', 'sun'],
      colors: ['orange', 'yellow'],
      mood: 'Serene and peaceful',
    };
    const parsed = CaptionSchema.safeParse(validData);
    expect(parsed.success).toBe(true);
  });

  it('rejects invalid CaptionSchema', () => {
    const invalidData = {
      title: '',
      caption: 'Too short',
    };
    const parsed = CaptionSchema.safeParse(invalidData);
    expect(parsed.success).toBe(false);
  });

  it('accepts only 64-character alphanumeric access tokens', () => {
    expect(accessTokenSchema.safeParse(accessToken).success).toBe(true);
    expect(accessTokenSchema.safeParse('short').success).toBe(false);
    expect(accessTokenSchema.safeParse(`${'a'.repeat(63)}-`).success).toBe(false);
  });

  it('accepts only same-origin callback redirects for the same access token', () => {
    expect(isExpectedCallbackRedirect(`https://image.example/auth/callback?token=${accessToken}`, 'https://cms.test', 'https://image.example', accessToken)).toBe(true);
    expect(isExpectedCallbackRedirect(`https://sentiment.example/auth/callback?token=${accessToken}`, 'https://cms.test', 'https://image.example', accessToken)).toBe(false);
    expect(isExpectedCallbackRedirect('https://image.example/auth/callback?token=other', 'https://cms.test', 'https://image.example', accessToken)).toBe(false);
    expect(isExpectedCallbackRedirect(`https://image.example/wrong?token=${accessToken}`, 'https://cms.test', 'https://image.example', accessToken)).toBe(false);
    expect(isExpectedCallbackRedirect(null, 'https://cms.test', 'https://image.example', accessToken)).toBe(false);
  });

  it('uses Gemini 2.5 Flash as default model', () => {
    expect(DEFAULT_GEMINI_MODEL).toBe('gemini-2.5-flash');
  });
});
