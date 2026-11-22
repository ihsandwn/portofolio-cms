# CSP & transport security — troubleshooting and deployment notes

## Actual state (verified in this repo)

- Laravel does **not** emit `Content-Security-Policy`. `App\Http\Middleware\SecurityHeaders`
  sets only `X-Content-Type-Options`, `X-Frame-Options: SAMEORIGIN`, `X-XSS-Protection`,
  `Referrer-Policy`, and `Permissions-Policy`, plus a diagnostic `X-CSP-Source: laravel:none`
  header so you can tell in DevTools that any CSP you see comes from somewhere else
  (proxy, host panel, CDN, extension, or `<meta>`). See `app/Http/Middleware/SecurityHeaders.php`.
- Each app in `ai-mvps/` sets its own CSP via `next.config.ts` `headers()`:
  restrictive `default-src 'self'`, `object-src 'none'`, `frame-ancestors 'none'`, with
  `script-src 'self' 'unsafe-inline'` because the Next.js App Router streams its RSC
  payload through inline `<script>` tags without a nonce here. Dev adds `'unsafe-eval'`
  and `ws:`/`wss:` for HMR only. Removing `'unsafe-inline'` without a nonce/hash
  migration breaks hydration (dropzones, buttons, results stay dead) — the config
  file carries this warning inline.
- `docker/nginx/app.conf` sets transport/security headers at the hosting boundary,
  including HSTS. HSTS only takes effect once the site is served over HTTPS with a
  valid certificate; on plain HTTP it is sent but ignored by browsers.

## Verify in deployment (not in this repo)

1. `curl -sI https://your-domain/ | grep -iE 'content-security-policy|strict-transport|frame-options'`
   — expect HSTS + frame options from nginx, no `Content-Security-Policy` from Laravel.
2. Open each AI demo page, DevTools → Network → document response headers — expect the
   Next.js CSP above.
3. If a proxy/CDN injects its own CSP, the page can break (blocked Livewire/Alpine/Vite
   scripts). Compare against the values above before reporting an app bug.
4. To tighten further (nonce-based `script-src`), the change must land in both the
   Laravel edge config **and** each `ai-mvps/*/next.config.ts`, then be re-verified with
   `npm run build` per app plus a browser pass over upload → analyze → results flows.

## History

PRD item 7 (`docs/portfolio-redesign-prd.md`) requires CSP/HSTS at the correct hosting
boundary with browser/deployment verification. Cross-checks: Laravel intentionally omits
CSP (this file explains why); nginx carries HSTS + safe headers; Next apps carry their
own CSP. A global Laravel CSP was deliberately not added because Vite dev (separate
origin), Livewire, and CDN scripts make an app-wide policy easy to get wrong.
