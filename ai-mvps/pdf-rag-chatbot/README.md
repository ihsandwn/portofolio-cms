# PDF RAG Chatbot - MVP

Next.js RAG chatbot. Upload PDFs, extract and chunk server-side, store embeddings in memory, then answer with source citations through Google Gemini.

## Security

- Laravel access token validates server-side before auth cookie creation and before every upload or chat request.
- Users request temporary access from Laravel AI Lab. Login is not required.
- Callback stores a 10-minute HTTP-only, `SameSite=Lax` cookie only after Laravel accepts the token.
- Rate limits are process-local. Use shared storage for horizontal production.
- PDF uploads require `%PDF-` magic bytes, `.pdf` extension, 10 MB limit, page limit, and extracted-text limit.
- PDF text is untrusted data. Prompt instructions embedded in PDFs are ignored.
- API errors hide provider and infrastructure details.

## RAG flow

1. Upload parses PDF pages, chunks text, and embeds chunks with Gemini.
2. In-memory store holds document metadata, page text, and vectors.
3. Chat embeds question, retrieves top matches, and streams grounded answer with citations.
4. Browser sends document ID and question after upload, never full PDF text.

Store is process-local and disappears on cold start. Use persistent object and vector storage for production.

## Quick start

```bash
npm install
npm run dev
```

Open through Laravel AI Lab access.

Create `.env.local`:

```env
GEMINI_API_KEY=your_gemini_key
GEMINI_MODEL=gemini-2.5-flash
GEMINI_EMBEDDING_MODEL=gemini-embedding-001
LARAVEL_API_URL=http://localhost:8000
NEXT_PUBLIC_LARAVEL_API_URL=http://localhost:8000
```

Set `GEMINI_MODEL` to another supported model to roll back without changing code.

## Validation

```bash
npm test
npm run lint
npm run typecheck
npm run build
```

## API

### POST /api/upload

Authenticated `multipart/form-data` request with `file` PDF up to 10 MB. Returns document metadata.

### POST /api/chat

Authenticated JSON request:

```json
{
  "documentId": "uuid",
  "question": "What does this document say?",
  "language": "en"
}
```

Streams a text response with source citations.

### GET /api/health

Public liveness probe.
