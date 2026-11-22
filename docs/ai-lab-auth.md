# AI Lab handoff and session authentication

Laravel is the only authentication authority. The four Next apps no longer trust query tokens, cookie shape, or an in-app verify endpoint.

## Flow

1. `AiLab\Show::requestAccess()` creates a 64-char alphanumeric grant, stores only `hash('sha256', $token)` on the access request, and exposes the raw grant to the Livewire view for one POST handoff.
2. Browser POSTs `token` to named route `ai-lab.auth` (`POST /ai-lab/auth`, web middleware, CSRF protected). Legacy `GET /ai-lab/auth/{token}` only redirects to `ai-lab.index`; it never authenticates.
3. `AiLabAuthController::handoff()` looks the grant up by hash, requires `approved` status and unexpired grant, consumes it atomically (`UPDATE ... WHERE token = hash`, one winner), resolves the target app from `portfolio.slug`, and redirects to `<app url>/auth/callback?code=<48-char single-use code>`. Code lives 60 seconds in the configured Laravel cache.
4. App callback GET exchanges the code once with `POST /api/mvp/auth/exchange` (`code`, `audience`, Bearer app secret), receives a 64-char opaque session token, and sets an HttpOnly SameSite=Lax cookie. Code is single-use; replay gets 401.
5. Every API route calls `verifySession(cookie)` → `POST /api/mvp/auth/verify` (`token`, `audience`, Bearer secret). Laravel checks audience, expiry, cache state and that the originating access request is still approved and unexpired. Missing config, failed request, wrong answer, or malformed token fail closed. Handoff is throttled 20/min, API exchange/verify 60/min.
6. Middleware uses the same verifier with a 15-second positive cache; it is a convenience, not the security boundary. No `pathname.includes('.')` bypass remains — static asset exclusions live in the `matcher`.

The callback URL contains only a 60-second single-use code, never a session token or grant. If POST body transport is mandatory end-to-end, the handoff form can instead submit directly to the app callback; current browser redirect semantics require GET.

## Required Laravel environment

No secret values are stored in this document. Set real values in the deployment environment only.

| Variable | Purpose |
| --- | --- |
| `AI_LAB_CACHE_STORE` | Laravel cache store shared by web/API processes. Must support atomic `add`/`forget`; `array` is test-only. |
| `AI_LAB_SENTIMENT_URL`, `AI_LAB_SENTIMENT_SECRET` | Audience `sentiment-analyzer`; secret >= 32 chars. |
| `AI_LAB_HR_URL`, `AI_LAB_HR_SECRET` | Audience `hr-screening`; secret >= 32 chars. |
| `AI_LAB_IMAGE_URL`, `AI_LAB_IMAGE_SECRET` | Audience `image-caption`; secret >= 32 chars. |
| `AI_LAB_PDF_URL`, `AI_LAB_PDF_SECRET` | Audience `pdf-rag-chatbot`; secret >= 32 chars. |

Config keys: `services.ai_lab.cache_store`, `services.ai_lab.apps.<audience>.url|secret`. Missing app config, short secret, missing URL or missing cache store returns 503 from exchange/verify.

## Required Next environment (per app)

| Variable | Purpose |
| --- | --- |
| `LARAVEL_API_URL` (server-only) | Base URL of this Laravel app. Falls back to `NEXT_PUBLIC_LARAVEL_API_URL`. |
| `MVP_APP_SECRET` (server-only) | Must equal the matching `AI_LAB_*_SECRET`. Missing or <32 chars ⇒ no auth possible. |
| `NEXT_PUBLIC_LARAVEL_API_URL` | Used by middleware to redirect unauthenticated page visits to `/ai-lab`. |

Cookie names: `mvp-access-sentiment`, `mvp-access-hr-screening`, `mvp-access-image-caption`, `mvp-access-pdf-rag-chatbot`. HttpOnly, SameSite=Lax, Secure in production, Max-Age clamped to session expiry.

## Parent integration steps

1. In `resources/views/livewire/public/ai-lab/show.blade.php`, replace lines 79-81 (the `<a href="{{ $generatedUrl }}">…</a>` block) with:
   ```blade
   @include('livewire.public.ai-lab.partials.handoff-form')
   ```
   The new partial owns the CSRF-protected POST form and hidden `token` field. Do not add the token to any URL.
2. Prefer a portfolio slug exactly equal to one of the four audiences above. `handoff()` resolves the audience by slug first, then falls back to matching the portfolio URL against the configured app URLs (the same rule `exchange()` uses) when exactly one app URL matches; otherwise it returns 503. Portfolios seeded before the audience-slug convention keep working through the URL fallback as long as each app URL is unique.
3. Set the environment variables above. Use the same cache store for the web handoff process and the API verify process.

## Endpoints

- `POST /ai-lab/auth` — web, CSRF, body `token` (64 alnum). 302 to audience callback with 60s `code` (48 alnum); 401 on invalid/replayed grant; 503 on missing config.
- `POST /api/mvp/auth/exchange` — Bearer app secret, JSON `{audience, code}` (48 alnum) or `{audience, token}` (64 alnum). Returns `{token, expires_at}`.
- `POST /api/mvp/auth/verify` — Bearer app secret, JSON `{audience, token}`. Returns `{valid, audience, expires_at}`; `Cache-Control: no-store`.

## Verification (actual runs)

- `vendor/bin/phpunit tests/Feature/AiLabAuthTest.php tests/Feature/AiLabAuthHandoffTest.php --do-not-cache-result` — exit 0 (audience, expiry, replay, forged token, config missing, POST handoff).
- Per app: `npm test`, `npm run typecheck`, `npm run lint`, `npm run build` — all four exit 0. Counts: sentiment 9/9, hr 6/6, image-caption 7/7, pdf-rag-chatbot 18/18.
