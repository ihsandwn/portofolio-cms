# CMS Portfolio — Design & Security Improvements

## Summary

This document describes the UI/UX, security, and architectural improvements applied to the CMS portfolio application for a senior programmer audience.

---

## 1. UI/UX Improvements

### Public Site (Senior Programmer Focus)
- **Simplified layout**: Removed duplicate markup, fixed malformed HTML (`</a>` duplication in nav)
- **Clear messaging**: Tech stack labels corrected ("comma-separated" vs "comma users separated")
- **Consistent design language**: Dark theme with Outfit + JetBrains Mono typography retained

### Admin Panel
- **Pages link added**: Admin sidebar now includes "Pages" under Content System (was missing)
- **Dashboard metrics**: Removed fake "12% vs last month" stat; replaced with actionable text
- **Information density**: Kept professional, data-focused layout suitable for technical users

### Auth (Login/Register)
- **Guest layout modernized**: Dark theme, clearer hierarchy, "Back to site" link
- **Security messaging**: "Secure admin access" footer for clarity

---

## 2. Security (OWASP-Oriented)

### XSS Mitigation
- **HTML Purifier integration**: All CMS content (pages, about, AI lab, blog text) now sanitized via `@safeHtml` directive
- **Blade directive**: `@safeHtml($content)` outputs sanitized HTML for user/CMS-sourced content
- **Service icons**: Left raw (admin-only content); consider input sanitization if untrusted admins exist

### Security Headers Middleware
- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: SAMEORIGIN`
- `X-XSS-Protection: 1; mode=block`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy`: Restricts geolocation, microphone, camera
- `Content-Security-Policy`: Restricts script/style sources (allows Livewire/Alpine)

### Path Traversal Fix
- Storage fallback route (`/storage/{path}`) now validates `realpath()` stays within `storage/app/public`
- Prevents `../` path traversal

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

---

## 6. Files Modified

| File | Change |
|------|--------|
| `app/Helpers/Sanitizer.php` | New: XSS sanitization wrapper |
| `app/Http/Middleware/SecurityHeaders.php` | New: OWASP security headers |
| `app/Providers/AppServiceProvider.php` | `@safeHtml` Blade directive |
| `bootstrap/app.php` | SecurityHeaders + web middleware |
| `routes/web.php` | Storage route path traversal fix |
| `config/purifier.php` | Extended allowed elements |
| `resources/views/components/layouts/app.blade.php` | Removed duplicate `</a>` |
| `resources/views/components/layouts/admin.blade.php` | Added Pages nav link |
| `resources/views/livewire/admin/dashboard.blade.php` | Fixed fake stat text |
| `resources/views/livewire/admin/portfolio/form.blade.php` | Typo fix |
| `resources/views/livewire/home.blade.php` | `@safeHtml` for CMS content |
| `resources/views/livewire/public/about.blade.php` | `@safeHtml` |
| `resources/views/livewire/public/portfolio/show.blade.php` | `@safeHtml` for markdown |
| `resources/views/livewire/public/ai-lab/show.blade.php` | `@safeHtml` for markdown |
| `resources/views/livewire/public/services/index.blade.php` | Service icons (admin content) |
| `resources/views/livewire/page.blade.php` | `@safeHtml` |
| `resources/views/layouts/guest.blade.php` | Modern dark theme |

---

## 7. Excluded Scope

- **ai-mvps** folder: Excluded from changes (different tech stack)
