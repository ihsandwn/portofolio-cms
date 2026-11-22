import { describe, it, expect, vi, afterEach } from 'vitest';
import { mimeFromExtension, matchesMagic } from '../lib/image-validation';
import { CaptionSchema } from '../lib/schemas';
import * as auth from '../lib/auth';
const { verifySession, AUDIENCE } = auth;

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

describe('Session verification', () => {
  afterEach(() => {
    vi.unstubAllGlobals();
    delete process.env.MVP_APP_SECRET;
    delete process.env.LARAVEL_API_URL;
  });

  it('fails closed when server config is missing', async () => {
    expect(await verifySession('a'.repeat(64))).toBeNull();
  });

  it('only trusts a Laravel-verified session and sends its audience', async () => {
    process.env.LARAVEL_API_URL = 'https://laravel.test';
    process.env.MVP_APP_SECRET = 's'.repeat(40);
    const token = 'a'.repeat(64);
    let sent: Record<string, unknown> = {};

    const fetchMock = vi.fn(async (_url: string, init?: RequestInit) => {
      sent = JSON.parse(String(init?.body));
      return new Response(JSON.stringify({ valid: true, expires_at: Math.floor(Date.now() / 1000) + 600 }), { status: 200 });
    });
    vi.stubGlobal('fetch', fetchMock);

    const session = await verifySession(token);
    expect(typeof session?.expiresAt).toBe('number');
    expect(sent).toMatchObject({ token, audience: AUDIENCE });

    fetchMock.mockResolvedValueOnce(new Response(JSON.stringify({ valid: false }), { status: 200 }));
    expect(await verifySession(token)).toBeNull();

    fetchMock.mockRejectedValueOnce(new Error('laravel down'));
    expect(await verifySession(token)).toBeNull();
  });

  it('rejects malformed tokens without calling Laravel', async () => {
    process.env.LARAVEL_API_URL = 'https://laravel.test';
    process.env.MVP_APP_SECRET = 's'.repeat(40);
    const fetchMock = vi.fn();
    vi.stubGlobal('fetch', fetchMock);

    expect(await verifySession('forged')).toBeNull();
    expect(await verifySession(undefined)).toBeNull();
    expect(fetchMock).not.toHaveBeenCalled();
  });
});
