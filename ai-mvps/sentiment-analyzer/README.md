# Sentiment Analyzer

Next.js sentiment analyzer backed by Gemini. Supports English and Indonesian UI/output explanations.

## Setup

Create `.env.local`:

```env
GEMINI_API_KEY=your_gemini_key
GEMINI_MODEL=gemini-2.5-flash
LARAVEL_API_URL=http://localhost:8000
NEXT_PUBLIC_LARAVEL_API_URL=http://localhost:8000
```

Request temporary access from Laravel AI Lab without logging in, then open Laravel's generated callback. The callback validates its 64-character access token against Laravel before setting a 10-minute HTTP-only cookie. Middleware checks token format, while `/api/analyze` revalidates the token with Laravel before calling Gemini. Set `GEMINI_MODEL` to another supported model to roll back without changing code.

```bash
npm install
npm run dev
```

Open `http://localhost:3001` through Laravel AI Lab access.

## Commands

```bash
npm test
npm run lint
npm run typecheck
npm run build
```

## API

`POST /api/analyze` requires a validated access cookie and same-origin browser request.

```json
{
  "text": "I love this product.",
  "language": "en"
}
```

Response fields are schema-validated: `sentiment`, `confidence`, `emotions`, `explanation`, and `analyzedAt`.

## Security

- Laravel callback token validation before cookie creation and API usage
- HTTP-only, same-site access cookie
- Same-origin API enforcement
- In-memory limit: 10 requests per IP/token per 10 minutes
- Zod validation at request, Gemini response, and client response boundaries
- Gemini JSON-only output with untrusted input boundaries
- Generic server errors without provider details
- CSP, clickjacking, MIME-sniffing, referrer, and permissions headers

## Limits

Text input is limited to 5,000 characters. The in-memory rate limiter resets on deployment or process restart.
