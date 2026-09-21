<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationMenuTest extends TestCase
{
    use RefreshDatabase;

    private Menu $menu;

    public function setUp(): void
    {
        parent::setUp();
        app()->setLocale('en');

        // Built here rather than leaning on the global seeders: the suite shares one
        // in-memory database, so $seed only fires for whichever class refreshes first.
        $this->menu = Menu::create(['name' => 'primary', 'is_active' => true]);

        $items = [
            ['title' => ['en' => 'About', 'id' => 'Tentang'], 'url' => '/about', 'order' => 1],
            ['title' => ['en' => 'Services', 'id' => 'Layanan'], 'url' => '/services', 'order' => 2],
            ['title' => ['en' => 'Portfolio', 'id' => 'Portofolio'], 'url' => '/portfolio', 'order' => 3],
            ['title' => ['en' => 'AI Lab', 'id' => 'Lab AI'], 'url' => '/ai-lab', 'order' => 4],
            ['title' => ['en' => 'Blog', 'id' => 'Artikel'], 'url' => '/blog', 'order' => 5],
        ];

        foreach ($items as $item) {
            MenuItem::create($item + ['menu_id' => $this->menu->id]);
        }

        Page::create([
            'slug' => 'about',
            'title' => ['en' => 'About Me', 'id' => 'Tentang Saya'],
            'content' => ['en' => 'About content', 'id' => 'Konten tentang'],
            'is_active' => true,
        ]);

        Page::create([
            'slug' => 'ai-lab',
            'title' => ['en' => 'AI Lab', 'id' => 'Lab AI'],
            'content' => ['en' => 'Lab content', 'id' => 'Konten lab'],
            'is_active' => true,
        ]);
    }

    private function navigationUrls(): array
    {
        return Menu::navigation()->pluck('url')->all();
    }

    public function test_menu_shows_every_active_item_in_order()
    {
        $this->assertSame(
            ['/about', '/services', '/portfolio', '/ai-lab', '/blog'],
            $this->navigationUrls()
        );
    }

    public function test_inactive_menu_hides_the_whole_navigation()
    {
        $this->menu->update(['is_active' => false]);

        $this->assertSame([], $this->navigationUrls());
    }

    public function test_missing_menu_returns_no_items()
    {
        $this->assertSame([], Menu::navigation('does-not-exist')->pluck('url')->all());
    }

    public function test_inactive_item_is_hidden()
    {
        MenuItem::where('url', '/portfolio')->update(['is_active' => false]);

        $this->assertNotContains('/portfolio', $this->navigationUrls());
        $this->assertContains('/about', $this->navigationUrls());
    }

    public function test_item_linked_to_a_draft_page_is_hidden()
    {
        Page::where('slug', 'about')->update(['is_active' => false]);

        $urls = $this->navigationUrls();

        $this->assertNotContains('/about', $urls);
        $this->assertContains('/ai-lab', $urls);
    }

    public function test_route_only_item_stays_when_it_has_no_page_record()
    {
        $this->assertDatabaseMissing('pages', ['slug' => 'blog']);

        $this->assertContains('/blog', $this->navigationUrls());
    }

    public function test_page_route_prefix_is_matched_against_the_page_slug()
    {
        MenuItem::create([
            'menu_id' => $this->menu->id,
            'title' => ['en' => 'AI Lab Page', 'id' => 'Halaman Lab AI'],
            'url' => '/p/ai-lab',
            'order' => 99,
        ]);

        $this->assertContains('/p/ai-lab', $this->navigationUrls());

        Page::where('slug', 'ai-lab')->update(['is_active' => false]);

        $this->assertNotContains('/p/ai-lab', $this->navigationUrls());
    }

    public function test_anchor_and_external_items_are_never_filtered_by_page_status()
    {
        MenuItem::create([
            'menu_id' => $this->menu->id,
            'title' => ['en' => 'Contact', 'id' => 'Kontak'],
            'url' => '#contact',
            'order' => 100,
        ]);

        MenuItem::create([
            'menu_id' => $this->menu->id,
            'title' => ['en' => 'LinkedIn', 'id' => 'LinkedIn'],
            'url' => 'https://www.linkedin.com/in/ichsan-dwi-nugraha-694a6a114',
            'order' => 101,
        ]);

        Page::query()->update(['is_active' => false]);

        $urls = $this->navigationUrls();

        $this->assertContains('#contact', $urls);
        $this->assertContains('https://www.linkedin.com/in/ichsan-dwi-nugraha-694a6a114', $urls);
    }

    public function test_home_page_renders_only_active_items_in_the_topbar()
    {
        MenuItem::where('url', '/blog')->update(['is_active' => false]);
        Page::where('slug', 'about')->update(['is_active' => false]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('href="/services"', false);
        $response->assertSee('href="/portfolio"', false);
        $response->assertDontSee('href="/blog"', false);
        $response->assertDontSee('href="/about"', false);
    }
}
