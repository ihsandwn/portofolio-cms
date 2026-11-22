<?php

namespace App\Helpers;

use Mews\Purifier\Facades\Purifier;
use DOMDocument;
use DOMElement;
use DOMXPath;

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

    /** HTMLPurifier drops SVG entirely, so icons get their own strict allowlist. */
    private const SVG_ELEMENTS = ['svg', 'g', 'defs', 'title', 'path', 'rect', 'circle', 'ellipse', 'line', 'polyline', 'polygon'];

    private const SVG_ATTRIBUTES = [
        'viewbox', 'xmlns', 'fill', 'stroke', 'stroke-width', 'stroke-linecap', 'stroke-linejoin',
        'stroke-dasharray', 'fill-rule', 'clip-rule', 'opacity', 'transform', 'role', 'aria-hidden',
        'focusable', 'd', 'x', 'y', 'x1', 'y1', 'x2', 'y2', 'cx', 'cy', 'r', 'rx', 'ry', 'width', 'height', 'points',
    ];

    public static function svg(mixed $icon): string
    {
        $icon = trim((string) ($icon ?? ''));
        if ($icon === '') {
            return '';
        }

        $doc = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $loaded = $doc->loadXML($icon, LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $doc->documentElement;
        if (! $loaded || ! $root instanceof DOMElement || strtolower($root->nodeName) !== 'svg') {
            return '';
        }

        $xpath = new DOMXPath($doc);
        foreach (iterator_to_array($xpath->query('//*')) as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }
            if (! in_array(strtolower($node->nodeName), self::SVG_ELEMENTS, true)) {
                $node->parentNode?->removeChild($node);
                continue;
            }
            foreach (iterator_to_array($node->attributes) as $attribute) {
                if (! in_array(strtolower($attribute->nodeName), self::SVG_ATTRIBUTES, true)) {
                    $node->removeAttribute($attribute->nodeName);
                }
            }
        }

        return $doc->saveXML($root) ?: '';
    }
}
