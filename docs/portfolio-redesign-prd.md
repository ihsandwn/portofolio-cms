# Portfolio Redesign PRD

Status: approved scope, implementation begins after this document
Date: 2026-11-21

## Product goal

Present Ichsan as a senior full-stack engineer through verifiable work, clear engineering decisions, and practical AI demonstrations. Serve recruiters and business owners equally. English is primary; Indonesian is available through the existing language switch.

## Phase 1 scope

- Public homepage and selected-work hierarchy.
- Portfolio case-study detail pages.
- AI Lab catalogue and demo-access journey across the four Next.js apps in `ai-mvps/`.
- Security defects that directly expose these public surfaces or demo APIs.
- Dependency advisory remediation only where compatible updates can be proven safe; do not overwrite existing user-edited lockfiles.

Out of scope: redesign of admin, services, blog, unrelated modules, deployment infrastructure, unverified business metrics, and database reset/migration commands.

## User needs and success criteria

Recruiters need to identify role, contribution, stack, trade-offs, and evidence quickly. Business owners need to understand the problem solved, demo limits, data handling, and how to discuss a project.

- Homepage communicates practical web delivery and selected work before long profile copy.
- Every project page distinguishes context, individual contribution, approach/trade-offs, evidence, and unmeasured outcomes.
- AI Lab explains task, sample input/output, limitations, and privacy/retention before upload or text submission.
- English/Indonesian switch covers all in-scope UI strings, including validation, access states, and errors.
- No unsupported claims (including shipped-system counts, accuracy scores, performance, scale, or integrations).
- Keyboard navigation, visible focus, semantic headings/forms, accessible status/error announcements, and reduced-motion support remain intact.

## Message hierarchy

Homepage: concise role/value proposition → selected work → project-backed capabilities → brief profile → AI Lab preview → contact.

Portfolio detail: project summary → context/status/role → problem and constraints → personal contribution → implementation choices/trade-offs → evidenced outcomes (or explicitly “not measured”) → verified links → contact CTA.

AI Lab: task and intended use → required input/output → privacy/retention → limitations and appropriate human review → access requirements/expiry → demo.

Prefer direct, specific language. Avoid generic superlatives and claims unsupported by repository, telemetry, or verifiable project evidence.

## Security requirements and audit findings

1. Critical: all four AI demo callback routes currently accept caller-provided query tokens and mint an access cookie without server-side Laravel verification. Middleware accepts cookie presence (with format-only checks in some apps). Replace with an authenticated, expiring, single-use server-to-server exchange; never grant access based on callback parameter shape alone.
2. High: Laravel AI Lab bearer token is placed in a URL and forwarded to the app query string; access token is stored in plaintext and is valid 10 minutes. Move credentials out of URLs, bind one-time exchange to expiry, and prevent replay. Add regression coverage for forged, expired, replayed, and valid grants.
3. High: `ai-mvps/pdf-rag-chatbot/app/api/upload/route.ts` logs extracted PDF text on empty-chunk errors. Remove document content logging; log only a correlation ID and safe error category.
4. High: admin-controlled service icon is emitted as raw HTML in homepage and services page. Remove raw SVG trust or sanitize using a strict SVG allowlist; add stored-XSS regression tests.
5. Medium: process-local AI rate limits are bypassable across instances; PDF upload lacks a limiter. Prefer shared storage-backed limits and explicitly validate trusted proxy configuration before relying on forwarded IP headers.
6. Medium: clarify upload/content handling and model limitations in UI, especially resume/PDF/imagery transmission, retention, and AI decision support. Do not imply AI assessments are validated hiring decisions.
7. Medium: add/verify restrictive CSP and production HSTS at the correct hosting boundary. Current Laravel middleware omits CSP; Next apps use `script-src 'unsafe-inline'`, requiring nonce/hash migration and deployment verification.
8. Fix README/runtime contradictions: Laravel 11/Livewire 3 vs Composer constraints Laravel 12/Livewire 4; unsupported “85% accuracy” and architecture claims; PDF RAG storage documentation conflicts with Neon pgvector implementation.

## Dependency and CVE audit

Audits against current configured registries on 2026-11-21 reported 9 Composer advisories, 14 root npm advisories, and 13–19 npm advisories per AI app. This is package-manager advisory data, not a claim that every advisory is exploitable in deployed paths. Before each dependency patch: capture package name, installed/affected range, advisory/CVE/GHSA, fixed version, direct/transitive path, runtime reachability, and regression test. Patch direct dependencies first with minimal compatible updates; regenerate only the affected lockfile in that app, preserving the three pre-existing modified lockfiles (`hr-screening`, `pdf-rag-chatbot`, `sentiment-analyzer`). Re-run audit and all app checks. No blanket force upgrade.

## Implementation plan

1. Save this PRD before code changes. Confirm repository status and protect user edits.
2. Close critical/high first-party auth, token, SVG-XSS, and document-log issues; write regression tests first where practical.
3. Review advisories app by app, obtain exact identifiers/fix paths, and patch only safe affected dependencies.
4. Reshape homepage, case-study information architecture, AI Lab language/privacy/access states, and full bilingual copy without inventing project outcomes.
5. Run Laravel build/tests and each AI app test/typecheck/build; test security regressions and verify changed files/status.
6. Report unresolved advisories, deployment-only requirements, and unverified claims as explicit follow-up items.

## Risks and decisions

- Secure cross-origin token exchange requires a server-to-server verification contract between Laravel and each AI app; do not ship a weaker client-side substitute. If shared production secret/configuration is unavailable, disable or retain server-side denial for protected demos and document the blocker.
- CSP nonce compatibility with Livewire/Vite and provider/CDN scripts must be validated in browser/deployment before enforcement.
- Dependency upgrades can break Next/Laravel compatibility; upgrades stay isolated and reversible by restoring only the affected dependency manifests/lockfile.
- No migration, DB reset, destructive cleanup, commit, or push is authorized.

## Rollback

Revert only newly created/modified task files and patches. Keep pre-existing user changes. Revert dependency changes per app without replacing unrelated lockfile edits. Do not roll back or reset database state.
