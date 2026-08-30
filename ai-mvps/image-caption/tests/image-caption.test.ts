import { describe, it, expect } from 'vitest';
import { mimeFromExtension, matchesMagic } from '../lib/image-validation';
import { CaptionSchema } from '../lib/schemas';

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
});
