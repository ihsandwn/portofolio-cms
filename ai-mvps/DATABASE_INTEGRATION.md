# AI Products - Knowledge Base

The MVP apps are self-contained. Keep secrets out of this repository; see each MVP README for setup, security, and limits.

## Requirements (all MVP apps)

- `.env.local` contains real secrets and is gitignored. Never commit it.
- API keys, database connection strings, tokens, cookies, private keys, and URLs containing secrets must never be committed.
- Rate limiting is process-local and resets on deploy; use a shared store such as Redis/Upstash for horizontally scaled production.
- PDF RAG embeddings are stored in memory and are cleared on restart or cold start.

## Production storage (optional)

Storage is intentionally not wired into the MVP apps. If persistence is added later, enforce row-level ownership, retention, and deletion.

| Product | Suggested storage | Document/row | Purpose |
| --- | --- | --- | --- |
| PDF RAG Chatbot | Vector-capable database or document store | `pdf_documents`, `vectors`, `chat_history` | Chunks, embeddings, and chat history |
| Sentiment Analyzer | PostgreSQL / MongoDB | `sentiment_analyses` | Analysis results and metadata |
| Image Caption | Object storage + DB | `image_captions` | Caption metadata; images in private bucket |
| HR Screening | PostgreSQL / MongoDB | `hr_screenings`, `resumes` | Match metadata and resume text |

Notes:
- `NEXT_PUBLIC_*` variables are public by design but still hold service configuration; validate them and never put private data behind them.
- Hashing/encrypting PII before sending it to AI providers and defining a retention/deletion policy is recommended for regulated data.
