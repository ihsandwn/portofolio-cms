<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\Portfolio;
use App\Models\AccessRequest;

class AiLabAccessRequestTest extends TestCase
{
    use RefreshDatabase;

    private function project(): Portfolio
    {
        return Portfolio::create([
            'title' => 'Image Caption',
            'slug' => 'image-caption',
            'type' => 'ai_agent',
            'description' => 'demo',
            'url' => 'https://image-caption.example.com',
        ]);
    }

    public function test_request_access_creates_token_and_url(): void
    {
        $p = $this->project();

        Livewire::test(\App\Livewire\Public\AiLab\Show::class, ['slug' => $p->slug])
            ->set('email', 'user@example.com')
            ->call('requestAccess')
            ->assertHasNoErrors()
            ->assertSet('accessRequested', true);

        $this->assertDatabaseHas('access_requests', [
            'portfolio_id' => $p->id,
            'email' => 'user@example.com',
            'status' => 'approved',
        ]);
    }

    public function test_invalid_email_is_rejected(): void
    {
        $p = $this->project();

        Livewire::test(\App\Livewire\Public\AiLab\Show::class, ['slug' => $p->slug])
            ->set('email', 'nope')
            ->call('requestAccess')
            ->assertHasErrors(['email']);
    }

    public function test_generated_link_redirects_to_the_mvp(): void
    {
        $p = $this->project();

        Livewire::test(\App\Livewire\Public\AiLab\Show::class, ['slug' => $p->slug])
            ->set('email', 'user@example.com')
            ->call('requestAccess');

        $token = AccessRequest::first()->token;

        $this->get(route('ai-lab.auth', ['token' => $token]))
            ->assertRedirect();
    }
}
