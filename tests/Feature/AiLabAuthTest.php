<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AiLabAuthTest extends TestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->loadEnvironmentFrom('.env.auth-test-not-present');
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'cache.default' => 'array']);
        DB::purge('sqlite');
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id(); $table->string('slug'); $table->string('url');
        });
        Schema::create('access_requests', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('portfolio_id'); $table->string('email');
            $table->string('token')->nullable(); $table->string('status'); $table->timestamp('expires_at')->nullable();
            $table->string('ip_address')->nullable(); $table->timestamps();
        });
        config(['services.ai_lab.cache_store' => 'array', 'services.ai_lab.apps' => [
            'sentiment-analyzer' => ['url' => 'https://sentiment.example', 'secret' => str_repeat('s', 48)],
            'hr-screening' => ['url' => 'https://hr.example', 'secret' => str_repeat('h', 48)],
        ]]);
        DB::table('portfolios')->insert(['id' => 1, 'slug' => 'sentiment-analyzer', 'url' => 'https://sentiment.example']);
        DB::table('access_requests')->insert(['portfolio_id' => 1, 'email' => 'test@example.org', 'token' => hash('sha256', str_repeat('a', 64)), 'status' => 'approved', 'expires_at' => now()->addMinutes(10)]);
    }

    public function test_exchange_is_audience_bound_single_use_and_session_verified(): void
    {
        $grant = str_repeat('a', 64);
        $body = ['token' => $grant, 'audience' => 'sentiment-analyzer'];
        $headers = ['Authorization' => 'Bearer '.str_repeat('s', 48)];
        $response = $this->postJson('/api/mvp/auth/exchange', $body, $headers)->assertOk();
        $session = $response->json('token');
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9]{64}$/', $session);
        $this->postJson('/api/mvp/auth/exchange', $body, $headers)->assertStatus(401);
        $this->postJson('/api/mvp/auth/verify', ['token' => $session, 'audience' => 'sentiment-analyzer'], $headers)->assertOk();
        $this->postJson('/api/mvp/auth/verify', ['token' => str_repeat('x', 64), 'audience' => 'sentiment-analyzer'], $headers)->assertStatus(401);
        $this->postJson('/api/mvp/auth/verify', ['token' => $session, 'audience' => 'hr-screening'], ['Authorization' => 'Bearer '.str_repeat('h', 48)])->assertStatus(401);
        $this->travel(11)->minutes();
        $this->postJson('/api/mvp/auth/verify', ['token' => $session, 'audience' => 'sentiment-analyzer'], $headers)->assertStatus(401);
    }

    public function test_unknown_audience_bad_secret_and_missing_config_fail_closed(): void
    {
        $headers = ['Authorization' => 'Bearer '.str_repeat('s', 48)];
        $body = ['token' => str_repeat('a', 64), 'audience' => 'sentiment-analyzer'];
        $this->postJson('/api/mvp/auth/exchange', $body, ['Authorization' => 'Bearer wrong'])->assertStatus(401);
        $this->postJson('/api/mvp/auth/exchange', ['audience' => 'unknown-app'] + $body, $headers)->assertStatus(503);
        config(['services.ai_lab.apps.sentiment-analyzer.secret' => null]);
        $this->postJson('/api/mvp/auth/exchange', $body, $headers)->assertStatus(503);
    }
}
