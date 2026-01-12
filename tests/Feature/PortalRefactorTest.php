<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Portfolio;

class PortalRefactorTest extends TestCase
{
    use RefreshDatabase;
    
    // Use the seeded data
    protected $seed = true;
    
    public function setUp(): void
    {
        parent::setUp();
        app()->setLocale('en');
    }

    public function test_about_page_is_accessible()
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('About Me');
    }

    public function test_services_page_is_accessible()
    {
        $response = $this->get('/services');
        $response->assertStatus(200);
        $response->assertSee('Expertise');
    }

    public function test_portfolio_index_is_accessible()
    {
        $response = $this->get('/portfolio');
        $response->assertStatus(200);
        $response->assertSee('Selected Works');
    }

    public function test_portfolio_show_is_accessible()
    {
        // Get a web portfolio
        $portfolio = Portfolio::where('type', 'web')->first();
        
        if ($portfolio) {
            $response = $this->get(route('portfolio.show', $portfolio->slug));
            $response->assertStatus(200);
            $response->assertSee($portfolio->title); // Implicitly casts TranslatableContent
        } else {
            $this->markTestSkipped('No web portfolio found via Seeder.');
        }
    }

    public function test_ai_lab_index_is_accessible()
    {
        $response = $this->get('/ai-lab');
        $response->assertStatus(200);
        $response->assertSee('AI Laboratory');
    }

    public function test_ai_lab_show_is_accessible()
    {
         // Get an AI portfolio
        $project = Portfolio::where('type', 'ai_agent')->first();
        
        if ($project) {
            $response = $this->get(route('ai-lab.show', $project->slug));
            $response->assertStatus(200);
            $response->assertSee($project->title);
        } else {
             $this->markTestSkipped('No AI portfolio found from Seeder.');
        }
    }
    public function test_homepage_case_study_links_are_correct()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        
        // Check for Featured AI Lab link (if any)
        $aiProject = Portfolio::where('type', 'ai_agent')->where('is_featured', true)->first();
        if ($aiProject) {
            $response->assertSee(route('ai-lab.show', $aiProject->slug));
        }

        // Check for Featured Portfolio link (if any)
        $webProject = Portfolio::where('type', 'web')->where('is_featured', true)->first();
        if ($webProject) {
            $response->assertSee(route('portfolio.show', $webProject->slug));
        }
    }
    public function test_portfolio_index_contains_all_types_with_correct_links()
    {
        $response = $this->get('/portfolio');
        $response->assertStatus(200);

        // Check for AI Lab link
        $aiProject = Portfolio::where('type', 'ai_agent')->first();
        if ($aiProject) {
            $response->assertSee(route('ai-lab.show', $aiProject->slug));
        }

        // Check for Web Portfolio link
        $webProject = Portfolio::where('type', 'web')->where('is_featured', true)->first();
        if ($webProject) {
            $response->assertSee(route('portfolio.show', $webProject->slug));
        }
    }
}
