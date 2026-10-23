# Vercel + Neon Setup (PDF RAG Chatbot)

Storage moved from in-memory `Map` to Neon Postgres + pgvector. Without this, upload and chat hit different serverless instances and chat returns 404 `Document unavailable`.

Do these steps AFTER pushing the code.

## 1. Add Neon to the Vercel project
1. Vercel Dashboard -> project -> Storage (or Marketplace) -> add **Neon Postgres**.
2. Connect to Production and Preview (Development optional).
3. Pick DB region close to the Function region.
4. This injects `DATABASE_URL` (pooled). If not injected, copy the **pooled** connection string from Neon (host contains `-pooler`, `sslmode=require`) and add it manually.

## 2. Create tables (once per database)
Neon Console -> SQL Editor, paste all of `db/schema.sql` and run. It is idempotent.
It does `CREATE EXTENSION IF NOT EXISTS vector`, then creates `documents` and `document_chunks` (`vector(3072)`).
Use a separate Neon branch/DB for Preview if you want isolation; run the schema there too.

## 3. Environment variables (Project -> Settings -> Environment Variables)
| Name | Value |
| --- | --- |
| `DATABASE_URL` | Neon pooled URL (secret) |
| `GEMINI_API_KEY` | Google AI Studio key (secret) |
| `EMBEDDING_MODEL` | `gemini-embedding-001` (3072 dims, matches schema) |
| `GEMINI_MODEL` | `gemini-2.5-flash` |
| `DOCUMENT_TTL_MS` | `1800000` (optional) |
| `MAX_FILE_SIZE` | `4000000` (optional; Vercel body limit is ~4.5 MB) |

Plus existing vars for the Laravel access flow (`LARAVEL_API_URL`, `NEXT_PUBLIC_LARAVEL_API_URL`, session/rate-limit vars) - keep as they are.
Delete `GEMINI_EMBEDDING_MODEL` if set (e.g. `text-embedding-004`); the app reads `EMBEDDING_MODEL`.
Do NOT change `EMBEDDING_MODEL` to a model with a different dimension without migrating `document_chunks.embedding`.

## 4. Redeploy
Env changes apply only to new deployments. Push, or Deployments -> Redeploy (clear build cache not required).

## 5. Verify
1. `GET /api/health` -> `{"status":"ok","storage":"postgres",...}`. `503` means `DATABASE_URL` wrong or schema missing; read Function logs.
2. Open via the Laravel access flow, upload a small PDF -> 200 with `document.id`.
3. Ask several questions in a row (hits different instances) -> answers with `Sources: p.N`, no 404.
4. Neon SQL Editor: `SELECT id, filename, expires_at FROM documents;` and `SELECT count(*) FROM document_chunks;` show rows.
5. Wait past TTL, ask again -> 404 `Document unavailable` (expected); expired rows are purged on the next upload.
6. Upload file > 4 MB -> 400/413, not 500.
7. Redeploy, ask about a document uploaded before it (within TTL) -> still works.

## Troubleshooting
- 404 `Document unavailable` right after upload: `DATABASE_URL` differs between deployments, or the session cookie changed (documents are bound to the session).
- 500 on upload: Function logs. Likely `DATABASE_URL is not set`, `relation "documents" does not exist` (step 2 skipped), `type "vector" does not exist`, or an embedding dimension mismatch (wrong `EMBEDDING_MODEL`).
- `@next/swc` version warning: install with `npm ci`.

## Limits / notes
- No vector index: pgvector indexes cap at 2000 dims; search is filtered per document so exact scan is cheap. Revisit if dims are reduced.
- Original PDF is not stored; only text chunks and embeddings, for `DOCUMENT_TTL_MS`.
- Optional: Vercel Cron hitting a cleanup route hourly if upload traffic is sparse.
- Not verified against a live Neon: local tests use PGlite (real Postgres + pgvector in WASM).
