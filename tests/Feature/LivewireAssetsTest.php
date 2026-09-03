<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Guards against the "livewire.min.js 404" class of bug.
 *
 * When public/vendor/livewire exists, Livewire points its <script src> at that
 * folder instead of its own PHP route. On any deploy that does not ship (or
 * refresh) that folder, the browser requests a file that is not there, gets the
 * HTML 404 page back, and refuses to execute it — so no wire:click ever fires.
 * Letting Livewire serve its own JS removes the whole failure mode.
 */
class LivewireAssetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_livewire_assets_are_not_committed(): void
    {
        $this->assertDirectoryDoesNotExist(
            public_path('vendor/livewire'),
            'public/vendor/livewire must not be published. It makes Livewire emit a static <script src> '
            .'that 404s whenever the folder is missing or stale on the server. '
            .'Run: php artisan livewire:unpublish --force'
        );
    }

    public function test_livewire_script_tag_points_at_a_reachable_route(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertMatchesRegularExpression(
            '/<script src="([^"]*)"[^>]*data-update-uri=/',
            $html,
            'Livewire did not inject its script tag into the page.'
        );

        preg_match('/<script src="([^"]*)"[^>]*data-update-uri=/', $html, $matches);
        $src = html_entity_decode($matches[1]);

        $this->assertStringNotContainsString(
            '/vendor/livewire/',
            $src,
            'Livewire is serving JS from public/vendor/livewire. That path is a plain file on disk; '
            .'if it is absent on the server the request returns the HTML 404 page and every '
            .'wire:click silently stops working.'
        );

        $path = parse_url($src, PHP_URL_PATH);

        $response = $this->get($path);
        $response->assertOk();
        $this->assertStringContainsString(
            'javascript',
            (string) $response->headers->get('Content-Type'),
            'Livewire JS route must return a JavaScript content type, not HTML.'
        );
    }

    public function test_livewire_assets_are_not_stale_when_published(): void
    {
        $published = public_path('vendor/livewire/manifest.json');

        if (! file_exists($published)) {
            $this->assertTrue(true, 'No published assets — Livewire serves its own JS.');

            return;
        }

        $dist = base_path('vendor/livewire/livewire/dist/manifest.json');

        $this->assertSame(
            json_decode((string) file_get_contents($dist), true),
            json_decode((string) file_get_contents($published), true),
            'Published Livewire assets are out of date with the installed package. '
            .'Run: php artisan livewire:unpublish --force'
        );
    }
}
