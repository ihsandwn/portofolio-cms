<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Page;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Session;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;
    
    protected $seed = true;

    public function test_locale_switching_persists_in_session()
    {
        // 1. Initial state (default 'en')
        $this->get('/')->assertSuccessful();
        $this->assertEquals(config('app.locale'), app()->getLocale());

        // 2. Switch to 'id' via Livewire component logic simulation
        // Since Livewire switch is just Session::put, we simulate that
        $this->withSession(['locale' => 'id'])->get('/')->assertSuccessful();
        
        // Assert locale is 'id' for the request
        $this->assertEquals('id', app()->getLocale());
    }

    public function test_models_translate_content_based_on_locale()
    {
        // Set app locale to 'id'
        app()->setLocale('id');

        // Check Menu Item
        $menuItem = MenuItem::whereJsonContains('title->en', 'Blog')->first();
        $this->assertEquals('Artikel', (string)$menuItem->title);

        // Check Page Content
        $page = Page::where('slug', 'about')->first();
        $this->assertEquals('Tentang Saya', (string)$page->title);
        $this->assertStringContainsString('Membangun Masa Depan', (string)$page->content);
        
        // Assert structure consistency (Mission/Vision headers exist in ID)
        $this->assertStringContainsString('Misi Saya', (string)$page->content);
        $this->assertStringContainsString('Visi Saya', (string)$page->content);

        // Check Blog Post
        $post = \App\Models\BlogPost::where('slug', 'future-of-laravel')->first();
        $this->assertEquals('Masa Depan Laravel', (string)$post->title);
        $this->assertEquals('Menjelajahi struktur ramping Laravel 11, Reverb, dan masa depan cloud-native dari framework ini.', (string)$post->excerpt);
        
        // Check Blog Blocks (via accessor)
        $firstBlock = $post->blocks[0];
        $this->assertStringContainsString('Era Baru Minimalisme', $firstBlock['data']);
    }
    
    public function test_default_locale_fallback()
    {
        app()->setLocale('fr'); // Unsupported locale
        
        // Should fallback to 'en' content if 'fr' is missing
        $menuItem = MenuItem::whereJsonContains('title->en', 'Blog')->first();
        $this->assertEquals('Blog', (string)$menuItem->title);
    }
}
