# CMS Portfolio — Design & Security Improvements

## Summary

This document describes the UI/UX, security, and architectural improvements applied to the CMS portfolio application for a senior programmer audience.

The **public site and shared shells** follow a **light “Technical Architect” blueprint** theme: M3-style semantic color tokens in Tailwind, **Inter** for body/headlines and **Space Grotesk** for labels, sharp corners, and layout patterns aligned with the Google **Stitch** exports under `.cursor/stitch/stitch/` (e.g. `home_profile_minimalist`, `portfolio_minimalist`).

---

## 1. UI/UX Improvements

### Public site — blueprint redesign (Stitch-aligned structure)

- **Theme**: Light surface backgrounds, `text-on-background` / `text-secondary`, primary teal (`primary` family from tokens), not a dark programmer theme.
- **Typography**: Inter + Space Grotesk (Google Fonts in `components/layouts/app.blade.php`); label styling uses `font-label`, uppercase microcopy, wide tracking where appropriate.
- **Global shell** (`resources/views/components/layouts/app.blade.php`):
  - **Width**: `max-w-7xl` with horizontal padding `px-6` for alignment with Stitch mocks.
  - **Background**: Subtle **blueprint dot grid** (`.blueprint-grid` in `resources/css/app.css`).
  - **Nav**: Frosted bar on scroll; brand as compact **IDN** mark + **uppercase label** name; primary menu from CMS; optional **terminal-style** icon before Contact; `wire:navigate` on same-origin links.
  - **Guide lines**: Fixed **vertical hairlines** at `left-6` / `right-6` (subtle anchors, hidden on very small viewports).
  - **Footer**: Compact **uppercase label** treatment, links styled like technical spec footers.
- **Home** (`resources/views/livewire/home.blade.php` + `app/Livewire/Home.php`):
  - **Hero**: `grid-cols-12` — **7/5** split: copy left; right **square schematic panel** (corner brackets, inline SVG topology, caption `FIG_01 // SYSTEM_TOPOLOGY`).
  - **Title**: Large headline (`md:text-[48px]`) with a **second line** in `text-outline` (configurable via settings, see below).
  - **Body**: Tighter `text-[13px] max-w-md`; CTAs `text-[11px]` uppercase.
  - **Core expertise**: **Tight grid** `grid-cols-2 md:grid-cols-4 gap-px` with shared outer border (spreadsheet-style cells), not spaced cards.
  - **About / profile band**: `md:grid-cols-12` — metadata column (location, availability, **total portfolio count**), plus two panels (philosophy quote + **tech chips** aggregated from portfolio `tech_stack`); full about page content remains below when present.
  - **Selected work**: **Table** layout (UID, project, tech, role, reference link) with loading skeleton rows; filters unchanged (All / Web / AI).
- **Portfolio index** (`resources/views/livewire/public/portfolio/index.blade.php`): Same **table-first** index pattern as the Stitch portfolio screen (horizontal scroll + `min-w` on small screens).
- **Utilities**: `.mono-num` in `app.css` for tabular / UID-style columns.

**Optional `home` settings** (group `home`, keys in `Setting`): `hero_subline`, `hero_philosophy`, `profile_location`, `profile_availability` — fallbacks and translations exist in `lang/en.json` and `lang/id.json`.

**Design reference**: Static HTML under `.cursor/stitch/stitch/*/code.html` (not shipped to production); Tailwind token names mirror those exports.

### Admin panel

- **Pages link**: Admin sidebar includes "Pages" under Content System.
- **Dashboard metrics**: Removed fake "12% vs last month" stat; replaced with actionable text.
- **Information density**: Professional, data-focused layout; **light** admin shell aligned with the same token system (`components/layouts/admin.blade.php`).

### Auth (login / register / guest)

- **Guest layout** (`resources/views/layouts/guest.blade.php`): **Light blueprint** — surface background, bordered card, `blueprint-grid` backdrop, "Back to site" + "Secure admin access" footer (not a dark-only theme).
- Volt/Breeze profile shells use the same semantic tokens where applicable.

---

## 2. Security (OWASP-Oriented)

### XSS Mitigation

- **HTML Purifier integration**: CMS content (pages, about, AI lab, blog text) sanitized via `@safeHtml` directive.
- **Blade directive**: `@safeHtml($content)` outputs sanitized HTML for user/CMS-sourced content.
- **Service icons**: Left raw (admin-only content); consider input sanitization if untrusted admins exist.

### Security Headers Middleware

- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: SAMEORIGIN`
- `X-XSS-Protection: 1; mode=block`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy`: Restricts geolocation, microphone, camera
- `Content-Security-Policy`: Restricts script/style sources (allows Livewire/Alpine)

### Path Traversal Fix

- Storage fallback route (`/storage/{path}`) validates `realpath()` stays within `storage/app/public`.
- Prevents `../` path traversal.

---

## 3. Package Updates & Vulnerabilities

### Composer

- **PHPUnit**: Updated to `^11.5.50` (CVE-2026-24765 fixed)
- **Remaining advisories**: `psy/psysh` and `symfony/process` — dev-only; run `composer audit` for latest

### NPM

- **Axios**: Updated via `npm audit fix` (DoS via `__proto__` fixed)
- **Result**: 0 vulnerabilities

---

## 4. Migrations & Database

### MySQL & PostgreSQL Support

- Existing migrations use Laravel's Schema builder; **no changes required**
- `enum()` columns: MySQL uses native ENUM; PostgreSQL uses `VARCHAR` + CHECK constraint
- `json()` and other types are supported on both

---

## 5. Architecture Notes

### Sanitizer Helper

- `App\Helpers\Sanitizer::clean()` — central XSS sanitization
- Handles `Stringable` (e.g. TranslatableContent) and nullable values

### Purifier Config

- `config/purifier.php`: Extended `HTML.Allowed` with `h2,h3,h4,h5,h6` for rich content
- SVG not allowed in default config (HTMLPurifier limitation); service icons output raw

### Front-end tokens

- **`tailwind.config.js`**: Semantic color map (surface, primary, outline-variant, etc.), `fontFamily.headline` / `body` / `label`, sharp `borderRadius`, `transitionDuration.blueprint`.
- **`resources/css/app.css`**: `.blueprint-grid`, `.content-section`, `.prose-blueprint`, `.mono-num`.

---

## 6. Files Modified (high level)

### Security & core (historical)

| File | Change |
|------|--------|
| `app/Helpers/Sanitizer.php` | XSS sanitization wrapper |
| `app/Http/Middleware/SecurityHeaders.php` | OWASP security headers |
| `app/Providers/AppServiceProvider.php` | `@safeHtml` Blade directive |
| `bootstrap/app.php` | SecurityHeaders + web middleware |
| `routes/web.php` | Storage route path traversal fix |
| `config/purifier.php` | Extended allowed elements |
| `resources/views/livewire/admin/portfolio/form.blade.php` | Typo fix |

### Blueprint redesign (public UI)

| File | Change |
|------|--------|
| `tailwind.config.js` | Stitch-aligned semantic tokens, fonts, radius, transitions |
| `resources/css/app.css` | Blueprint grid, content/prose utilities, `.mono-num` |
| `resources/views/components/layouts/app.blade.php` | Light shell, `max-w-7xl`, guide lines, nav/footer, fonts |
| `resources/views/components/layouts/admin.blade.php` | Light admin shell, fonts |
| `resources/views/layouts/guest.blade.php` | Light blueprint guest/auth shell |
| `resources/views/layouts/app.blade.php` | Breeze profile shell tokens (where used) |
| `resources/views/livewire/home.blade.php` | 12-col hero + schematic, competency grid, profile band, portfolio table |
| `app/Livewire/Home.php` | `portfolioTotal`, `techChips` for profile section |
| `resources/views/livewire/public/portfolio/index.blade.php` | Table-based project index |
| Anonymous Blade components | Buttons, inputs, nav, modal, etc. — semantic tokens |
| `lang/en.json`, `lang/id.json` | New copy keys for blueprint UI |

### CMS content safety (sanitized output)

| File | Change |
|------|--------|
| `resources/views/livewire/home.blade.php` | `@safeHtml` for about / AI content |
| `resources/views/livewire/public/about.blade.php` | `@safeHtml` |
| `resources/views/livewire/public/portfolio/show.blade.php` | `@safeHtml` for markdown |
| `resources/views/livewire/public/ai-lab/show.blade.php` | `@safeHtml` for markdown |
| `resources/views/livewire/public/services/index.blade.php` | Service icons (admin content) |
| `resources/views/livewire/page.blade.php` | `@safeHtml` |

### Admin UX

| File | Change |
|------|--------|
| `resources/views/components/layouts/admin.blade.php` | Pages nav link |
| `resources/views/livewire/admin/dashboard.blade.php` | Removed fake stat text |

---

## 7. Excluded Scope

- **ai-mvps** folder: Excluded from changes (different tech stack)
