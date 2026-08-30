import { z } from 'zod';

export const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'] as const;
export type AllowedMimeType = (typeof ALLOWED_MIME_TYPES)[number];

export const MAX_FILE_SIZE = 10 * 1024 * 1024;

const EXTENSION_TO_MIME: Record<string, AllowedMimeType> = {
  jpg: 'image/jpeg',
  jpeg: 'image/jpeg',
  png: 'image/png',
  webp: 'image/webp',
  gif: 'image/gif',
};

export function mimeFromExtension(filename: string): AllowedMimeType | null {
  const ext = filename.toLowerCase().split('.').pop() ?? '';
  return EXTENSION_TO_MIME[ext] ?? null;
}

const MAGIC: Record<AllowedMimeType, { bytes: number[]; mask?: number[] }> = {
  'image/jpeg': { bytes: [0xff, 0xd8, 0xff] },
  'image/png': { bytes: [0x89, 0x50, 0x4e, 0x47, 0x0d, 0x0a, 0x1a, 0x0a] },
  'image/webp': { bytes: [0x52, 0x49, 0x46, 0x46], mask: [0xff, 0xff, 0xff, 0xff] },
  'image/gif': { bytes: [0x47, 0x49, 0x46, 0x38, 0x37, 0x61] },
};

export function matchesMagic(bytes: Uint8Array, mime: AllowedMimeType): boolean {
  const sig = MAGIC[mime];
  if (!sig) return false;
  if (bytes.length < sig.bytes.length) return false;
  return sig.bytes.every((b, i) => (bytes[i] & (sig.mask?.[i] ?? 0xff)) === (b & (sig.mask?.[i] ?? 0xff)));
}

const BUFFER_LIMIT_ERROR = new Error('Image too large');

export async function bufferDoesNotExceed(file: File, maxBytes: number): Promise<boolean> {
  const arrayBuffer = await file.arrayBuffer();
  if (arrayBuffer.byteLength > maxBytes) throw BUFFER_LIMIT_ERROR;
  return true;
}

export async function validateImageUpload(file: File): Promise<{ buffer: ArrayBuffer; mime: AllowedMimeType }> {
  const mime = mimeFromExtension(file.name);
  if (!mime || (file.type && file.type !== mime)) {
    throw new Error('Only JPG, PNG, WebP, and GIF images are allowed');
  }
  await bufferDoesNotExceed(file, MAX_FILE_SIZE);
  const buffer = await file.arrayBuffer();
  if (!matchesMagic(new Uint8Array(buffer.slice(0, 12)), mime)) {
    throw new Error('File content does not match the image type');
  }
  return { buffer, mime };
}