# PDF RAG Chatbot - MVP

A Next.js RAG chatbot: upload PDFs, extract and chunk text server-side, store embeddings in memory, and answer questions with source citations using Google Gemini AI.

## Access flow

Laravel AI Lab is the sole access gate:

1. The user requests access from the Laravel AI Lab route.
2. Laravel creates and approves a temporary access request.
3. Laravel redirects to `/auth/callback?token=...` in this app.
4. The callback writes an HTTP-only access cookie.
5. Middleware protects app pages and protected API routes while that cookie exists.

No Laravel Sanctum token or token revalidation is required by this MVP.

## Security hardening

- In-memory per-IP+token rate limiting: 10 requests / 10 minutes (process-local; use shared store for horizontal production).
- PDF uploads require `%PDF-` magic bytes, `.pdf` extension, 10 MB limit, max pages and extracted text length enforced.
- Prompt injection defense: uploaded document text is handled as untrusted input; embedded instructions are ignored.
- Generic client errors; provider and infrastructure details are logged server-side only.
- CSP, `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: no-referrer`, `Permissions-Policy`, `poweredByHeader: false`.

## RAG architecture

1. Upload parses a PDF into pages, chunks text with overlap, then embeds chunks with Gemini.
2. An in-memory server store holds document ID, page texts, chunk embeddings, and metadata for 30 minutes.
3. Chat embeds the question, retrieves relevant chunks with cosine similarity, then streams an answer grounded only in those chunks with page citations.
4. The browser does not resend the full document after upload; it sends only the question and document ID.

**Limitation:** storage is process-local and cleared by restarts or serverless cold starts. Persistence is not configured in this MVP.

## Quick start

```bash
npm install
npm run dev
```

Open `http://localhost:3000` through the Laravel AI Lab access flow.

Required `.env.local` variables:

```env
GEMINI_API_KEY=your-valid-google-ai-studio-key
GEMINI_MODEL=gemini-2.5-flash
GEMINI_EMBEDDING_MODEL=text-embedding-004
LARAVEL_API_URL=http://localhost:8000
NEXT_PUBLIC_LARAVEL_API_URL=http://localhost:8000
```

Get a Gemini API key from [Google AI Studio](https://aistudio.google.com/apikey). The same key supports both chat and embeddings for this app.

## Validation

```bash
npx vitest run
npm run lint
npm run build
```

## API

### POST /api/upload

`multipart/form-data` — `file` (PDF, 10 MB max). Returns:

```json
{
  "success": true,
  "document": {
    "id": "...",
    "filename": "document.pdf",
    "chunkCount": 12,
    "uploadedAt": "..."
  }
}
```

### POST /api/chat

JSON `{ documentId, question, history?, language }`. Streams a response with inline `[p. 2]` source citations for uploaded pages.

### GET /api/health

Public liveness probe.

## Project structure

```text
pdf-rag-chatbot/
├── app/
│   ├── api/
│   │   ├── chat/route.ts          # Streaming RAG chat
│   │   ├── health/route.ts
│   │   └── upload/route.ts        # PDF ingest and embedding
│   ├── auth/callback/route.ts     # Laravel AI Lab callback
│   ├── layout.tsx
│   └── page.tsx
├── components/
│   ├── ChatInterface.tsx
│   ├── LanguageToggle.tsx
│   └── PDFUploader.tsx
├── lib/
│   ├── gemini.ts                  # Gemini client (chat and embeddings)
│   ├── pdf-processor.ts           # PDF parsing and validation
│   ├── rag.ts                     # Chunking and cosine retrieval
│   ├── rate-limit.ts              # Baseline per-process limiter
│   └── storage.ts                 # In-memory document store
├── middleware.ts                  # Cookie-based access protection
└── package.json
```

## Next steps

- [ ] Multi-document sessions
- [ ] Document deletion and retention policy
- [ ] Export conversation and citations
- [ ] Deploy with a shared Redis rate limiter
- [ ] Add abuse and billing observability
