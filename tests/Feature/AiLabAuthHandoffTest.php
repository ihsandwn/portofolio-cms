<?php

namespace Tests\Feature;

use App\Models\AccessRequest;
use App\Models\Portfolio;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class AiLabAuthHandoffTest extends TestCase
{
    use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'cache.default' => 'array']);
        DB::purge('sqlite');
        Schema::create('portfolios', function (Blueprint $table) { $table->id(); $table->string('slug'); $table->string('url'); $table->timestamps(); });
        Schema::create('access_requests', function (Blueprint $table) { $table->id(); $table->unsignedBigInteger('portfolio_id'); $table->string('email'); $table->string('token')->nullable(); $table->string('status'); $table->timestamp('expires_at')->nullable(); $table->string('ip_address')->nullable(); $table->timestamps(); });
        config(['services.ai_lab.cache_store' => 'array', 'services.ai_lab.apps.sentiment-analyzer' => ['url' => 'https://sentiment.example', 'secret' => str_repeat('s', 48)]]);
        Portfolio::create(['id' => 1, 'slug' => 'sentiment-analyzer', 'url' => 'https://sentiment.example']);
        AccessRequest::create(['portfolio_id' => 1, 'email' => 'test@example.org', 'token' => hash('sha256', str_repeat('a', 64)), 'status' => 'approved', 'expires_at' => now()->addMinutes(10), 'ip_address' => '127.0.0.1']);
    }

    public function test_handoff_consumes_grant_and_redirects_to_audience_code(): void
    {
        $response = $this->post(route('ai-lab.auth'), ['token' => str_repeat('a', 64)]);
        $this->assertSame(302, $response->status());
        $this->assertStringStartsWith(rtrim(config('services.ai_lab.apps.sentiment-analyzer.url'), '/').'/auth/callback?code=', $response->headers->get('Location'));
        $this->assertDatabaseMissing('access_requests', ['token' => hash('sha256', str_repeat('a', 64))]);
    }

    public function test_handoff_rejects_malformed_token(): void
    {
        $this->post(route('ai-lab.auth'), ['token' => 'short'])->assertSessionHasErrors('token');
    }

    public function test_handoff_rejects_forged_or_expired_grant(): void
    {
        $this->post(route('ai-lab.auth'), ['token' => str_repeat('z', 64)])->assertStatus(401);
        $grant = AccessRequest::first();
        $grant->expires_at = now()->subMinute();
        $grant->save();
        $this->post(route('ai-lab.auth'), ['token' => str_repeat('a', 64)])->assertStatus(401);
    }

    public function test_handoff_replay_and_missing_config_fail_closed(): void
    {
        $this->post(route('ai-lab.auth'), ['token' => str_repeat('a', 64)])->assertStatus(302);
        $this->post(route('ai-lab.auth'), ['token' => str_repeat('a', 64)])->assertStatus(401);

        AccessRequest::create(['portfolio_id' => 1, 'email' => 'second@example.org', 'token' => hash('sha256', str_repeat('b', 64)), 'status' => 'approved', 'expires_at' => now()->addMinutes(10)]);
        config(['services.ai_lab.apps.sentiment-analyzer.secret' => 'too-short']);
        $this->post(route('ai-lab.auth'), ['token' => str_repeat('b', 64)])->assertStatus(503);
    }

    public function test_handoff_resolves_legacy_slug_by_matching_app_url(): void
    {
        // Mirrors the real seeded row: pre-convention slug, localhost app URL.
        // A second portfolio sharing the same URL must not break resolution,
        // because uniqueness is counted across configured apps, not portfolios.
        $portfolio = Portfolio::create(['slug' => 'pdf-rag-chatbot-beta', 'url' => 'http://localhost:3000']);
        Portfolio::create(['slug' => 'rag-knowledge-retrieval-system', 'url' => 'http://localhost:3000']);
        config(['services.ai_lab.apps.pdf-rag-chatbot' => ['url' => 'http://localhost:3000', 'secret' => str_repeat('p', 48)]]);
        AccessRequest::create(['portfolio_id' => $portfolio->id, 'email' => 'legacy@example.org', 'token' => hash('sha256', str_repeat('c', 64)), 'status' => 'approved', 'expires_at' => now()->addMinutes(10)]);

        $response = $this->post(route('ai-lab.auth'), ['token' => str_repeat('c', 64)]);
        $this->assertSame(302, $response->status());
        $this->assertStringStartsWith('http://localhost:3000/auth/callback?code=', $response->headers->get('Location'));
    }

    public function test_handoff_rejects_legacy_slug_without_url_match(): void
    {
        $portfolio = Portfolio::create(['slug' => 'renamed-demo', 'url' => 'https://nowhere.example']);
        AccessRequest::create(['portfolio_id' => $portfolio->id, 'email' => 'legacy@example.org', 'token' => hash('sha256', str_repeat('d', 64)), 'status' => 'approved', 'expires_at' => now()->addMinutes(10)]);

        $this->post(route('ai-lab.auth'), ['token' => str_repeat('d', 64)])->assertStatus(503);
    }
}
