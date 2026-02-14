<?php

namespace App\Helpers;

use Mews\Purifier\Facades\Purifier;

/**
 * Sanitize user/CMS content for safe HTML output.
 * OWASP: Prevents XSS via stored content.
 */
class Sanitizer
{
    public static function clean(mixed $html): string
    {
        $html = $html instanceof \Stringable ? (string) $html : (string) ($html ?? '');
        if (blank($html)) {
            return '';
        }

        return Purifier::clean($html);
    }
}
