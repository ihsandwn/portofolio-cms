import { afterEach, describe, expect, it, vi } from 'vitest';

const { embedText } = vi.hoisted(() => ({
  embedText: vi.fn(),
}));

vi.mock('@/lib/gemini', () => ({ embedText }));

import { POST } from './route';
import { documentCount } from '@/lib/storage';

function pdf(text: string): Buffer {
  const stream = `BT /F1 18 Tf 72 720 Td (${text}) Tj ET`;
  const objects = [
    '<</Type/Catalog/Pages 2 0 R>>',
    '<</Type/Pages/Kids[3 0 R]/Count 1>>',
    '<</Type/Page/Parent 2 0 R/MediaBox[0 0 612 792]/Contents 4 0 R/Resources<</Font<</F1 5 0 R>>>>>>',
    `<</Length ${stream.length}>>\nstream\n${stream}\nendstream`,
    '<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>',
  ];
  let out = '%PDF-1.4\n';
  const offsets = objects.map((body, i) => {
    const offset = out.length;
    out += `${i + 1} 0 obj\n${body}\nendobj\n`;
    return offset;
  });
  const xref = out.length;
  out += `xref\n0 ${objects.length + 1}\n0000000000 65535 f \n`;
  out += offsets.map((o) => `${String(o).padStart(10, '0')} 00000 n \n`).join('');
  out += `trailer<</Size ${objects.length + 1}/Root 1 0 R>>\nstartxref\n${xref}\n%%EOF`;
  return Buffer.from(out, 'latin1');
}

function upload(name = 'guide.pdf') {
  const form = new FormData();
  form.append('file', new File([new Uint8Array(pdf('Llamas live in the Andes'))], name, { type: 'application/pdf' }));
  return POST(new Request('http://localhost/api/upload', { method: 'POST', body: form }) as never);
}

describe('POST /api/upload', () => {
  afterEach(() => embedText.mockReset());

  it('parses a real PDF, embeds its text and stores the document', async () => {
    embedText.mockResolvedValue([0.1, 0.2]);
    const before = documentCount();

    const response = await upload('my guide.pdf');

    expect(response.status).toBe(200);
    await expect(response.json()).resolves.toMatchObject({
      success: true,
      document: { filename: 'my_guide.pdf', chunkCount: 1 },
    });
    expect(embedText).toHaveBeenCalledWith(expect.stringContaining('Llamas live in the Andes'));
    expect(documentCount()).toBe(before + 1);
  });

  it('returns generic 500 and stores nothing when the embedding provider fails', async () => {
    embedText.mockRejectedValue(new Error('provider failure'));
    const before = documentCount();
    vi.spyOn(console, 'error').mockImplementation(() => {});

    const response = await upload();

    expect(response.status).toBe(500);
    await expect(response.json()).resolves.toEqual({ error: 'Unable to process upload' });
    expect(documentCount()).toBe(before);
  });

  it('rejects a non-PDF payload with 400 before calling the provider', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    const form = new FormData();
    form.append('file', new File(['hello'], 'fake.pdf', { type: 'application/pdf' }));

    const response = await POST(new Request('http://localhost/api/upload', { method: 'POST', body: form }) as never);

    expect(response.status).toBe(400);
    expect(embedText).not.toHaveBeenCalled();
  });
});

