# AI Products - Knowledge Base

While the MVP apps are hardened and self-contained, keep secrets out of this repository. See each MVP's README for setup, security, and limits.

## Requirements (all MVP apps)

- `.env.local` contains real secrets and is gitignored. Never commit it.
- API keys, Mongo/Supabase connection strings, tokens, cookies, private keys and URLs containing secrets must never be committed.
- Rate limiting is process-local and resets on deploy; use a shared store (Redis/Upstash) for horizontally scaled production.
- PDF RAG embeddings are in-memory; use pgvector/vector DB for persistent production RAG.

## Production storage (planned / optional)

Storage is intentionally not wired into the MVP apps (stateless baseline). If you add persistence, follow these schemas per product, enforce row-level ownership, retention, and deletion:

| Product | Suggested storage | Document/row | Purpose |
| --- | --- | --- | --- |
| PDF RAG Chatbot | pgvector / MongoDB | `pdf_documents`, `vectors`, `chat_history` | Chunks + embeddings + chat |
| Sentiment Analyzer | PostgreSQL / MongoDB | `sentiment_analyses` | Analysis results + metadata |
| Image Caption | Object storage + DB | `image_captions` | Caption metadata; images in private bucket |
| HR Screening | PostgreSQL / MongoDB | `hr_screenings`, `resumes` | Match metadata + resume text |

Notes:
- `NEXT_PUBLIC_*` variables are public by design but still hold service configuration; validate them and never put private data behind them.
- Hashing/encrypting PII before AI providers and defining retention/deletion policy is recommended for regulated data.