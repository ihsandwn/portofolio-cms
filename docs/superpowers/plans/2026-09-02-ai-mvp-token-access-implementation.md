# AI MVP token access implementation plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Samakan akses token tanpa login dan default model Gemini pada `image-caption`, `pdf-rag-chatbot`, serta `sentiment-analyzer` dengan kontrak `hr-screening`.

**Architecture:** Masing-masing MVP tetap memiliki helper auth lokal karena dibangun dan dideploy mandiri. Helper menganggap valid hanya redirect Laravel 3xx menuju callback dengan token yang sama. Middleware memeriksa format cookie murah, sementara callback dan route AI memvalidasi token aktif ke Laravel sebelum memberi akses atau memanggil Gemini.

**Tech Stack:** Next.js 15, TypeScript, Zod, Node test runner, Vitest, Google Generative AI SDK, Laravel redirect endpoint.

## Global constraints

- Jangan ubah `Dockerfile` atau root `package-lock.json`; keduanya perubahan awal user.
- Jangan ubah `hr-screening`, UI halaman, alur login Laravel, atau masa berlaku token 600 detik.
- Jangan buat package shared baru; tiga MVP bersifat independen dan `ai-mvps/shared` sudah dihapus.
- Cookie token wajib `httpOnly`, `sameSite: 'lax'`, `secure` di production, `path: '/'`, `maxAge: 600`.
- Token valid hanya tepat 64 karakter alfanumerik.
- Helper validasi memakai `LARAVEL_API_URL` dengan fallback `NEXT_PUBLIC_LARAVEL_API_URL`, menerima origin `http:`/`https:` saja, timeout 5 detik, `redirect: 'manual'`, dan `cache: 'no-store'`.
- API harus fail closed dengan HTTP 401 ketika token hilang, format invalid, Laravel tidak tersedia, atau redirect tidak valid.
- Health endpoint tetap publik. Middleware tidak melakukan fetch Laravel.
- Default `GEMINI_MODEL` adalah `gemini-3-flash-preview`; environment tetap override default.
- Jangan log token, email, API key, PDF, atau gambar.
- Jangan commit atau push tanpa instruksi user.

---

## File map

- `ai-mvps/image-caption/lib/auth.ts`: ganti kontrak JSON `/api/validate-token` dengan validator redirect Laravel dan export schema token.
- `ai-mvps/image-caption/middleware.ts`: sederhanakan menjadi policy middleware yang sama dengan `hr-screening`.
- `ai-mvps/image-caption/app/auth/callback/route.ts`: gunakan boolean validator baru dan simpan cookie 600 detik.
- `ai-mvps/image-caption/app/api/caption/route.ts`: validasi cookie ke Laravel sebelum membaca upload.
- `ai-mvps/image-caption/lib/gemini-vision.ts`: ganti default model.
- `ai-mvps/image-caption/tests/image-caption.test.ts`: tambah test token schema dan default model.
- `ai-mvps/image-caption/README.md`: perbarui default model dan kontrak akses.
- `ai-mvps/sentiment-analyzer/lib/auth.ts`: pertahankan validator redirect yang sudah sesuai dan export kontrak bila perlu.
- `ai-mvps/sentiment-analyzer/middleware.ts`: samakan route public, token-format gate, redirect Laravel terkonfigurasi, dan HTTP 503.
- `ai-mvps/sentiment-analyzer/lib/gemini.ts`: ganti default model.
- `ai-mvps/sentiment-analyzer/tests/hardening.test.ts`: tambah test default model secara pure helper atau export const default model.
- `ai-mvps/sentiment-analyzer/README.md`: perbarui model dan flow akses.
- `ai-mvps/pdf-rag-chatbot/lib/laravel-auth.ts`: ganti verifier POST endpoint environment dengan validator redirect Laravel; export schema serta `getClientIp` bila route membutuhkannya.
- `ai-mvps/pdf-rag-chatbot/middleware.ts`: samakan policy middleware dengan `hr-screening`.
- `ai-mvps/pdf-rag-chatbot/app/auth/callback/route.ts`: gunakan helper standar dan cookie token 600 detik.
- `ai-mvps/pdf-rag-chatbot/app/api/upload/route.ts`: validasi aktif sebelum memproses PDF.
- `ai-mvps/pdf-rag-chatbot/app/api/chat/route.ts`: validasi aktif sebelum embedding atau chat.
- `ai-mvps/pdf-rag-chatbot/app/api/auth/verify/route.ts`: hapus jika `search_files` membuktikan tidak ada consumer.
- `ai-mvps/pdf-rag-chatbot/lib/gemini.ts`: ganti default model.
- `ai-mvps/pdf-rag-chatbot/lib/rag.test.ts`: tambah unit test schema dan default model atau buat `lib/laravel-auth.test.ts` untuk contract auth.
- `ai-mvps/pdf-rag-chatbot/package.json`: tambah script `test` dan `typecheck` bila dependency Vitest/TypeScript sudah tersedia.
- `ai-mvps/pdf-rag-chatbot/README.md`: perbarui default model dan flow akses.
- `ai-mvps/.env.example`: perbarui nilai default `GEMINI_MODEL`; jangan isi credential.

### Task 1: Image Caption token contract dan model

**Files:**
- Modify: `ai-mvps/image-caption/lib/auth.ts`
- Modify: `ai-mvps/image-caption/middleware.ts`
- Modify: `ai-mvps/image-caption/app/auth/callback/route.ts`
- Modify: `ai-mvps/image-caption/app/api/caption/route.ts`
- Modify: `ai-mvps/image-caption/lib/gemini-vision.ts`
- Modify: `ai-mvps/image-caption/tests/image-caption.test.ts`
- Modify: `ai-mvps/image-caption/README.md`

**Interfaces:**
- Produces: `accessTokenSchema: z.ZodString`, `validateAccessToken(token: string): Promise<boolean>`, `getClientIp(headers: Headers): string`, `DEFAULT_GEMINI_MODEL = 'gemini-3-flash-preview'`.
- Consumes: Laravel `GET /ai-lab/auth/{token}` redirect contract and cookie `mvp-access-image-caption`.

- [ ] **Step 1: Add failing auth-contract tests**

Add a test for 64-character alphanumeric tokens and reject short or hyphenated tokens. Extract a pure `isExpectedCallbackRedirect(location: string | null, baseUrl: string, token: string): boolean` from `lib/auth.ts` to make redirect acceptance testable without network mocking.

```ts
expect(accessTokenSchema.safeParse('a'.repeat(64)).success).toBe(true);
expect(accessTokenSchema.safeParse('short').success).toBe(false);
expect(accessTokenSchema.safeParse(`${'a'.repeat(63)}-`).success).toBe(false);
expect(isExpectedCallbackRedirect('/auth/callback?token=' + token, 'https://cms.test', token)).toBe(true);
expect(isExpectedCallbackRedirect('/auth/callback?token=other', 'https://cms.test', token)).toBe(false);
expect(isExpectedCallbackRedirect('/wrong?token=' + token, 'https://cms.test', token)).toBe(false);
```

- [ ] **Step 2: Run targeted test and verify RED**

Run: `npm test -- --run tests/image-caption.test.ts`

Expected: FAIL because `accessTokenSchema` and `isExpectedCallbackRedirect` are not exported by `lib/auth.ts`.

- [ ] **Step 3: Implement redirect validator**

Replace `validateToken()` with this contract. Preserve error hiding by returning `false`, not provider errors.

```ts
export const accessTokenSchema = z.string().regex(/^[A-Za-z0-9]{64}$/);

function laravelBaseUrl(): string | null { /* environment origin validation */ }

export function isExpectedCallbackRedirect(location: string | null, baseUrl: string, token: string): boolean {
  if (!location) return false;
  const redirectUrl = new URL(location, baseUrl);
  return redirectUrl.pathname === '/auth/callback'
    && redirectUrl.searchParams.get('token') === token;
}

export async function validateAccessToken(token: string): Promise<boolean> { /* 3xx redirect/manual/no-store/5s */ }
```

Use `getClientIp()` matching `hr-screening` for API rate-limit identity.

- [ ] **Step 4: Run targeted test and verify GREEN**

Run: `npm test -- --run tests/image-caption.test.ts`

Expected: PASS with auth-contract tests and existing image validation tests.

- [ ] **Step 5: Add API and middleware gates**

Replace image middleware’s `validateToken()` calls and nonexistent `/api/validate-token` dependency. Follow this ordered policy:

```ts
if (callback || health || nextAsset || staticFile) return NextResponse.next();
const token = request.cookies.get('mvp-access-image-caption')?.value;
if (token && accessTokenSchema.safeParse(token).success) return NextResponse.next();
if (pathname.startsWith('/api')) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
if (!laravelUrl) return NextResponse.json({ error: 'Authentication service unavailable' }, { status: 503 });
return NextResponse.redirect(new URL('/ai-lab', laravelUrl));
```

In `app/api/caption/route.ts`, read cookie and invoke `validateAccessToken()` before `request.formData()`. Return generic HTTP 401 on failure. Do not forward upload content before authentication.

In callback, reject `!token || !(await validateAccessToken(token))`; retain cookie flags and `maxAge: 600`.

- [ ] **Step 6: Change default model**

Export and use this constant from `lib/gemini-vision.ts`:

```ts
export const DEFAULT_GEMINI_MODEL = 'gemini-3-flash-preview';
const model = new GoogleGenerativeAI(apiKey).getGenerativeModel({
  model: process.env.GEMINI_MODEL || DEFAULT_GEMINI_MODEL,
  // existing generation config
});
```

Add an assertion that the constant equals `gemini-3-flash-preview`.

- [ ] **Step 7: Update documentation**

Change README default to `GEMINI_MODEL=gemini-3-flash-preview`. Document user flow: request temporary access in Laravel, open generated callback URL, no Laravel login, API validates token server-side on every AI request.

- [ ] **Step 8: Run package checks**

Run from `ai-mvps/image-caption`:

```bash
npm test
npm run lint
npm run typecheck
npm run build
```

Expected: each command exit 0. Record any pre-existing dependency or environment blocker without masking it.

### Task 2: Sentiment Analyzer middleware parity dan model

**Files:**
- Modify: `ai-mvps/sentiment-analyzer/middleware.ts`
- Modify: `ai-mvps/sentiment-analyzer/lib/gemini.ts`
- Modify: `ai-mvps/sentiment-analyzer/tests/hardening.test.ts`
- Modify: `ai-mvps/sentiment-analyzer/README.md`

**Interfaces:**
- Consumes: existing `accessTokenSchema` and `validateAccessToken()` in `lib/auth.ts`.
- Produces: consistent middleware behavior and `DEFAULT_GEMINI_MODEL = 'gemini-3-flash-preview'`.
- Must not alter: existing active validation in `app/api/analyze/route.ts`.

- [ ] **Step 1: Add failing default-model test**

Import `DEFAULT_GEMINI_MODEL` from `lib/gemini.ts` in `tests/hardening.test.ts`.

```ts
test('uses Gemini 3 Flash Preview as default model', () => {
  assert.equal(DEFAULT_GEMINI_MODEL, 'gemini-3-flash-preview');
});
```

- [ ] **Step 2: Run targeted test and verify RED**

Run: `npm test -- --test-name-pattern "default model"`

Expected: FAIL because `DEFAULT_GEMINI_MODEL` is not exported.

- [ ] **Step 3: Implement minimal default-model export**

In `lib/gemini.ts`:

```ts
export const DEFAULT_GEMINI_MODEL = 'gemini-3-flash-preview';
// existing GoogleGenerativeAI setup
model: process.env.GEMINI_MODEL || DEFAULT_GEMINI_MODEL,
```

- [ ] **Step 4: Run targeted test and verify GREEN**

Run: `npm test -- --test-name-pattern "default model"`

Expected: PASS.

- [ ] **Step 5: Replace weak cookie-existence middleware**

Use `accessTokenSchema` in `middleware.ts`, allow `/api/health`, callback and assets, return JSON HTTP 401 for invalid/missing API cookie, redirect unauthenticated pages to configured Laravel `/ai-lab`, and return HTTP 503 if Laravel URL is absent. Do not fetch Laravel in middleware. Retain `app/api/analyze/route.ts` active validation unchanged.

- [ ] **Step 6: Update README**

Set default `GEMINI_MODEL=gemini-3-flash-preview`. Describe token callback and server-side per-request validation. Remove claims that imply Laravel login is required.

- [ ] **Step 7: Run package checks**

Run from `ai-mvps/sentiment-analyzer`:

```bash
npm test
npm run lint
npm run typecheck
npm run build
```

Expected: each command exit 0.

### Task 3: PDF RAG unified token validator, API gates, model

**Files:**
- Modify: `ai-mvps/pdf-rag-chatbot/lib/laravel-auth.ts`
- Modify: `ai-mvps/pdf-rag-chatbot/middleware.ts`
- Modify: `ai-mvps/pdf-rag-chatbot/app/auth/callback/route.ts`
- Modify: `ai-mvps/pdf-rag-chatbot/app/api/upload/route.ts`
- Modify: `ai-mvps/pdf-rag-chatbot/app/api/chat/route.ts`
- Delete: `ai-mvps/pdf-rag-chatbot/app/api/auth/verify/route.ts` only after consumer search returns zero results outside that route.
- Modify: `ai-mvps/pdf-rag-chatbot/lib/gemini.ts`
- Modify: `ai-mvps/pdf-rag-chatbot/lib/rag.test.ts` or Create: `ai-mvps/pdf-rag-chatbot/lib/laravel-auth.test.ts`
- Modify: `ai-mvps/pdf-rag-chatbot/package.json`
- Modify: `ai-mvps/pdf-rag-chatbot/README.md`

**Interfaces:**
- Produces: `accessTokenSchema`, `isExpectedCallbackRedirect()`, `validateAccessToken()`, `getClientIp()`, and `DEFAULT_GEMINI_MODEL`.
- Consumes: cookie `mvp-access-pdf-rag`, Laravel `GET /ai-lab/auth/{token}` redirect contract, existing `RateLimiter` APIs in upload and chat routes.

- [ ] **Step 1: Identify API consumers and current route contracts**

Read `app/api/upload/route.ts` and `app/api/chat/route.ts`. Search the entire `ai-mvps/pdf-rag-chatbot` folder for `/api/auth/verify`, `verifyLaravelAccessToken`, `LARAVEL_TOKEN_VERIFY_URL`, `mvp-access-pdf-rag`, and `RateLimiter`. Record every affected call site before deleting anything.

- [ ] **Step 2: Add failing auth-contract tests**

Create or extend test file with the same pure tests as Task 1:

```ts
expect(accessTokenSchema.safeParse('a'.repeat(64)).success).toBe(true);
expect(accessTokenSchema.safeParse('invalid-token').success).toBe(false);
expect(isExpectedCallbackRedirect('/auth/callback?token=' + token, 'https://cms.test', token)).toBe(true);
expect(isExpectedCallbackRedirect('/auth/callback?token=other', 'https://cms.test', token)).toBe(false);
```

Add default-model assertion:

```ts
expect(DEFAULT_GEMINI_MODEL).toBe('gemini-3-flash-preview');
```

- [ ] **Step 3: Run test and verify RED**

Run: `npx vitest run lib/laravel-auth.test.ts`

Expected: FAIL because exports and/or test file do not yet exist.

- [ ] **Step 4: Implement auth helper and callback**

Replace `verifyLaravelAccessToken()` with same redirect-based contract from Task 1. Callback uses `validateAccessToken()` and only sets `mvp-access-pdf-rag`; do not set non-HTTP-only `mvp-user-email`, because it is not required for authorization and exposes user data to client JavaScript.

- [ ] **Step 5: Run auth test and verify GREEN**

Run: `npx vitest run lib/laravel-auth.test.ts`

Expected: PASS.

- [ ] **Step 6: Add middleware and AI-route authorization**

Replace existing middleware with token-format policy from Task 1. Keep `/api/health` public. Remove `/api/auth/verify` from public list only after route deletion.

At the start of both upload and chat POST handlers:

```ts
const token = request.cookies.get('mvp-access-pdf-rag')?.value;
if (!token || !(await validateAccessToken(token))) {
  return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
}
```

Use existing request error style and rate-limit identity. Do not embed, extract, store, or stream data before authorization passes.

- [ ] **Step 7: Remove dead verify endpoint if unreferenced**

Delete `app/api/auth/verify/route.ts` only when search confirms no client, server, test, README, or middleware consumer. Remove `LARAVEL_TOKEN_VERIFY_URL` from documentation if endpoint is deleted.

- [ ] **Step 8: Change default model and package scripts**

Export `DEFAULT_GEMINI_MODEL = 'gemini-3-flash-preview'` from `lib/gemini.ts` and use it as environment fallback.

Add existing-tool scripts to package.json:

```json
"typecheck": "tsc --noEmit",
"test": "vitest run"
```

Do not change dependencies or lockfile because Vitest and TypeScript are already dev dependencies.

- [ ] **Step 9: Update README**

Set `GEMINI_MODEL=gemini-3-flash-preview`. Document redirect-based token validation, no login required after access approval, 10-minute cookie duration, and `GEMINI_MODEL` rollback override. Remove obsolete `LARAVEL_TOKEN_VERIFY_URL` setup instruction.

- [ ] **Step 10: Run package checks**

Run from `ai-mvps/pdf-rag-chatbot`:

```bash
npm test
npm run lint
npm run typecheck
npm run build
```

Expected: each command exit 0.

### Task 4: Shared environment documentation, cross-module audit, and final checks

**Files:**
- Modify: `ai-mvps/.env.example`
- Modify: all README files changed in Tasks 1-3 only if task-level doc update was incomplete.
- Modify: no production files unless audit finds a missed declared requirement.

**Interfaces:**
- Consumes: finalized three MVP auth and model contracts.
- Produces: deployment-facing environment documentation with no secrets.

- [ ] **Step 1: Add failing textual contract checks only where package harness supports it**

For every MVP that can import a pure `DEFAULT_GEMINI_MODEL`, assert its value. Do not test `.env.example` via brittle filesystem snapshots.

- [ ] **Step 2: Update environment example**

Set each non-secret `GEMINI_MODEL` example to:

```dotenv
GEMINI_MODEL=gemini-3-flash-preview
```

Keep API key placeholders. Remove obsolete `LARAVEL_TOKEN_VERIFY_URL` only for PDF RAG when no route consumes it.

- [ ] **Step 3: Run per-MVP focused verification after final mutation**

From each directory, run standalone canonical commands after all edits:

```bash
npm test
npm run lint
npm run typecheck
npm run build
```

For every command, inspect full output and exit code. If a command is unavailable, report the exact missing script and use installed equivalent only after reading package manifest.

- [ ] **Step 4: Run repository hygiene checks**

Run:

```bash
git diff --check -- ai-mvps/image-caption ai-mvps/sentiment-analyzer ai-mvps/pdf-rag-chatbot docs/superpowers
npm --prefix ai-mvps/image-caption run lint
npm --prefix ai-mvps/sentiment-analyzer run lint
npm --prefix ai-mvps/pdf-rag-chatbot run lint
```

Inspect `git status --short` and scoped diff. Confirm `Dockerfile` and root `package-lock.json` are untouched by this work.

- [ ] **Step 5: Thermo-nuclear review**

Review only changed scoped files for:

- duplicate token contracts or revived JSON verification endpoint;
- accidental edge-network validation in middleware;
- unauthenticated upload, embed, storage, or Gemini call;
- broken fail-closed paths;
- cookie security regression;
- source client key exposure;
- extra modes, flags, or shared abstractions;
- logic placed outside each MVP’s ownership boundary;
- stale docs or env variables;
- files enlarged beyond a justified focused responsibility.

Fix only confirmed scope defects. Re-run affected package checks after any fix.

- [ ] **Step 6: Final evidence report**

Report exact changed files, token contract behavior, model default, commands with exit codes, any blocked gate, deferred model-preview risk, and confirmation user-owned `Dockerfile` and root `package-lock.json` were not altered. Do not commit or push.

## Plan self-review

- Spec coverage: Tasks 1-3 implement callback, middleware, API active validation, 10-minute cookie, default model, and module documentation. Task 4 updates shared env docs, runs scope hygiene, and enforces quality review.
- No placeholders: every task lists exact paths, interfaces, test intent, commands, and behavior.
- Contract consistency: all three MVPs use `accessTokenSchema`, `validateAccessToken`, callback redirect check, HTTP-only 600-second cookie, and `DEFAULT_GEMINI_MODEL`.
- Intentional difference: `pdf-rag-chatbot` removes its client-readable email cookie because it is not authorization input. Confirm no UI reads it before deletion.
