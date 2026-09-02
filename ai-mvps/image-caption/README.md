# Image Caption

Authenticated Next.js image captioning app backed by Google Gemini Vision and Laravel Sanctum.

## Setup

```bash
npm install
npm run dev
```

Configure environment variables:

```env
GEMINI_API_KEY=your_key
GEMINI_MODEL=gemini-2.5-flash
LARAVEL_API_URL=http://localhost:8000
NEXT_PUBLIC_LARAVEL_API_URL=http://localhost:8000
```

Users request temporary access from Laravel AI Lab without logging in. Laravel sends approved users to `/auth/callback?token=...`. Callback verifies the token server-side before setting a 10-minute `HttpOnly`, `SameSite=Lax` cookie. Middleware checks the cookie format, and the caption API revalidates the token with Laravel before processing an image. `/api/health` remains public. Set `GEMINI_MODEL` to another supported model to roll back without changing code.

## Security

- Caption route requires Laravel token verification and has baseline per-process IP rate limiting.
- Uploads allow only JPEG, PNG, WebP, and GIF up to 10MB. Extension, declared MIME type, and magic bytes must agree.
- Gemini receives image data in memory; images are not persisted.
- Gemini uses JSON response mode. Zod validates AI output and shared API types.
- Generic client errors avoid leaking provider or server details.
- CSP, frame denial, MIME sniffing prevention, referrer, and permissions headers are configured.

For horizontally scaled production deployments, replace in-memory rate limiting with a shared Redis-backed limiter.

## API

`POST /api/caption` accepts `multipart/form-data`:

- `file`: one supported image, max 10MB
- `language`: `en` or `id`

Successful responses contain `success`, `filename`, `title`, `caption`, `categories`, `objects`, `colors`, `mood`, and `captionedAt`.

## Validation

```bash
npm test
npm run lint
npm run typecheck
npm run build
```

## Supported languages

English and Bahasa Indonesia. Changing language clears stale results so new analysis always matches selected language.
