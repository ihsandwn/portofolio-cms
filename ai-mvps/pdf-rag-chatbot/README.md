# PDF RAG Chatbot - MVP

A Next.js RAG chatbot: upload PDFs, server extracts & chunks text, stores in-memory embeddings, and answers questions with source citations using Google Gemini AI and Laravel Sanctum authentication.

## Security hardening

- Laravel access token revalidated server-side before auth cookie issuance and on every upload/chat request.
- In-memory per-IP+token rate limiting: 10 requests / 10 minutes (process-local; use shared store for horizontal production).
- PDF uploads require `%PDF-` magic bytes, `.pdf` extension, 10 MB limit, max pages & extracted text length enforced.
- Prompt injection defense: uploaded document text wrapped in untrusted-input delimiters; embedded instructions ignored.
- Zod validates request bodies, Gemini output (structured JSON mode), and client response boundaries.
- Generic client errors; no provider or infrastructure details leaked.
- CSP, `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: no-referrer`, `Permissions-Policy`, `poweredByHeader: false`.

## RAG architecture (MVP)

1. Upload parses PDF into pages, chunks with overlap, embeds chunks via Gemini embedding model.
2. In-memory server store holds document ID, page texts, chunk embeddings, and metadata.
3. Chat: user question is embedded, cosine similarity retrieves top-K relevant chunks, prompt grounded **only** in retrieved context with page/chunk source citations streamed back.
4. Browser never sends full document text after upload; only question + session context.

**Limitation:** store is process-local and vanishes on cold start. Replace with Supabase/pgvector or vector DB for persistent production.

## Quick start

```bash
npm install
npm run dev
```

Open `http://localhost:3000` through Laravel AI Lab access.

Required env (in `.env.local`):

```
GEMINI_API_KEY=...
GEMINI_MODEL=gemini-2.5-flash
GEMINI_EMBEDDING_MODEL=text-embedding-004
LARAVEL_API_URL=http://localhost:8000
NEXT_PUBLIC_LARAVEL_API_URL=http://localhost:8000
# optional override
# LARAVEL_TOKEN_VERIFY_URL=http://localhost:8000/api/validate-token
```

## Validation

```bash
npm test
npm run lint
npm run typecheck
npm run build
```

## API

### POST /api/auth/verify

Internal endpoint used by callback to re-validate Laravel token.

### POST /api/upload

`multipart/form-data` — `file` (PDF, 10 MB max). Returns `{ success, document: { id, filename, pageCount, chunkCount, uploadedAt } }`.

### POST /api/chat

JSON `{ documentId, question, history?, language }`. Streams text response with inline `[pN]` source citations referencing uploaded pages.

### GET /api/health

Public liveness probe.

## Project structure

```
pdf-rag-chatbot/
├── app/
│   ├── api/
│   │   ├── auth/verify/route.ts   # Laravel token verification
│   │   ├── chat/route.ts          # Streaming RAG chat
│   │   ├── health/route.ts
│   │   └── upload/route.ts        # PDF ingest & embedding
│   ├── layout.tsx
│   └── page.tsx
├── components/
│   ├── ChatInterface.tsx
│   ├── LanguageToggle.tsx
│   └── PDFUploader.tsx
├── lib/
│   ├── auth.ts                    # Laravel token validation
│   ├── gemini.ts                  # Gemini client (chat + embeddings)
│   ├── pdf-processor.ts           # PDF parse, magic bytes, chunking
│   ├── rag.ts                     # In-memory store + cosine retrieval
│   ├── rate-limit.ts              # Baseline per-process limiter
│   ├── schemas.ts                 # Zod request/response/AI types
│   └── storage.ts                 # (compat shim)
├── tests/
│   └── hardening.test.ts
├── vitest.config.ts
├── next.config.ts
├── eslint.config.mjs
├── tsconfig.json
└── package.json
```

## Next steps

- [ ] Replace in-memory store with Supabase pgvector or managed vector DB
- [ ] Add chat history persistence (Supabase/PostgreSQL)
- [ ] Multi-document sessions
- [ ] Document deletion + retention policy
- [ ] Export conversation / sources
- [ ] Deploy to Vercel with shared Redis rate limiter
- [ ] Add abuse/billing observability