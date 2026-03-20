<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * OWASP-oriented security headers (non-CSP).
 *
 * Content-Security-Policy is intentionally not set here: Vite dev (separate origin), Livewire,
 * CDNs, and reverse proxies make an app-wide CSP easy to get wrong. Add CSP at your edge
 * (nginx, Cloudflare “CSP”, etc.) once your real script/style/connect origins are known, or
 * use a package such as spatie/laravel-csp with environment-specific rules.
 *
 * @see docs/CSP-TROUBLESHOOTING.md
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        // Runtime evidence: Laravel does not emit Content-Security-Policy. If DevTools still shows CSP,
        // it comes from a proxy, host panel, CDN, extension, or <meta> — see docs/CSP-TROUBLESHOOTING.md
        $response->headers->set('X-CSP-Source', 'laravel:none');

        return $response;
    }
}
