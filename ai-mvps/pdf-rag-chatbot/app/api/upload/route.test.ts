import { afterAll, afterEach, beforeAll, describe, expect, it, vi } from 'vitest';

const { embedText, streamGroundedAnswer } = vi.hoisted(() => ({
  embedText: vi.fn(),
  streamGroundedAnswer: vi.fn(),
}));

vi.mock('@/lib/gemini', () => ({ embedText, streamGroundedAnswer }));
vi.mock('@/lib/db', () => ({ query: (sql: string, values?: unknown[]) => db.query(sql, values) }));

import { PGlite } from '@electric-sql/pglite';
import { vector } from '@electric-sql/pglite-pgvector';
import { readFileSync } from 'node:fs';
import { NextRequest } from 'next/server';
import { POST as chat } from '../chat/route';
const db = new PGlite({ extensions: { vector } });
const embedding = [1, ...Array(3071).fill(0)];
beforeAll(async () => { await db.exec(readFileSync('db/schema.sql', 'utf8')); }, 30_000);
afterAll(() => db.close());

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
  return POST(new NextRequest('http://localhost/api/upload', { method: 'POST', body: form, headers: { cookie: 'mvp-access-pdf-rag=owner-a' } }));
}

describe('POST /api/upload', () => {
  afterEach(() => { vi.restoreAllMocks(); embedText.mockReset(); streamGroundedAnswer.mockReset(); });

  it('parses a real PDF, embeds its text and stores the document', async () => {
    embedText.mockResolvedValue(embedding);
    const before = await documentCount();

    const response = await upload('my guide.pdf');

    expect(response.status).toBe(200);
    await expect(response.json()).resolves.toMatchObject({
      success: true,
      document: { filename: 'my_guide.pdf', chunkCount: 1 },
    });
    expect(embedText).toHaveBeenCalledWith(expect.stringContaining('Llamas live in the Andes'));
    expect(await documentCount()).toBe(before + 1);
  });

  it('chats using persisted upload, blocks another owner and missing auth', async () => {
    embedText.mockResolvedValue(embedding);
    streamGroundedAnswer.mockResolvedValue((async function* () { yield { text: () => 'Llamas live in the Andes [p. 1].' }; })());
    const uploaded = await (await upload()).json();
    const request = (token?: string) => new NextRequest('http://localhost/api/chat', {
      method: 'POST', body: JSON.stringify({ documentId: uploaded.document.id, question: 'Where do llamas live?' }),
      headers: token ? { cookie: `mvp-access-pdf-rag=${token}` } : {},
    });
    const response = await chat(request('owner-a'));
    expect(response.status).toBe(200);
    expect(await response.text()).toContain('Sources: p.1');
    expect((await chat(request('owner-b'))).status).toBe(404);
    expect((await chat(request())).status).toBe(401);
  });

  it('returns generic 500 and stores nothing when the embedding provider fails', async () => {
    embedText.mockRejectedValue(new Error('provider failure'));
    const before = await documentCount();
    vi.spyOn(console, 'error').mockImplementation(() => {});

    const response = await upload();

    expect(response.status).toBe(500);
    await expect(response.json()).resolves.toEqual({ error: 'Unable to process upload' });
    expect(await documentCount()).toBe(before);
  });

  it('rejects a non-PDF payload with 400 before calling the provider', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    const form = new FormData();
    form.append('file', new File(['hello'], 'fake.pdf', { type: 'application/pdf' }));

    const response = await POST(new NextRequest('http://localhost/api/upload', { method: 'POST', body: form, headers: { cookie: 'mvp-access-pdf-rag=owner-a' } }));

    expect(response.status).toBe(400);
    expect(embedText).not.toHaveBeenCalled();
  });
});

