<?php

namespace Tests\Feature;

use App\Livewire\Public\AiLab\Show;
use App\Models\AccessRequest;
use App\Models\Portfolio;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AiLabShowValidationMessageTest extends TestCase
{
    use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'cache.default' => 'array']);
        DB::purge('sqlite');
        Schema::create('portfolios', function (Blueprint $table) { $table->id(); $table->string('slug'); $table->string('type')->nullable(); $table->string('url')->nullable(); $table->timestamps(); });
        Schema::create('access_requests', function (Blueprint $table) { $table->id(); $table->unsignedBigInteger('portfolio_id'); $table->string('email'); $table->string('token')->nullable(); $table->string('status'); $table->timestamp('expires_at')->nullable(); $table->string('ip_address')->nullable(); $table->timestamps(); });
        Portfolio::create(['slug' => 'pdf-rag-chatbot', 'type' => 'ai_agent', 'url' => 'http://localhost:3000']);
    }

    private function messages(): array
    {
        $method = new \ReflectionMethod(Show::class, 'messages');
        $method->setAccessible(true);

        return $method->invoke(new Show());
    }

    public function test_request_access_validation_messages_are_bilingual(): void
    {
        app()->setLocale('en');
        $messages = $this->messages();
        $this->assertSame('A valid email address is required.', $messages['email.required']);
        $this->assertSame('Enter an email address in a valid format.', $messages['email.email']);

        app()->setLocale('id');
        $messages = $this->messages();
        $this->assertSame('Alamat email yang valid wajib diisi.', $messages['email.required']);
        $this->assertSame('Masukkan alamat email dengan format yang valid.', $messages['email.email']);
    }

    public function test_monthly_budget_allows_two_sessions_then_denies(): void
    {
        app()->setLocale('id');
        $project = Portfolio::where('slug', 'pdf-rag-chatbot')->first();

        $sessions = 0;
        foreach (['first', 'second', 'third'] as $attempt) {
            $component = new Show();
            $component->project = $project;
            $component->email = 'visitor@example.org';
            $component->requestAccess();
            if ($component->accessRequested) {
                $sessions++;
            }
        }

        $this->assertSame(2, $sessions);
        $this->assertSame(2, AccessRequest::where('email', 'visitor@example.org')->count());
        $this->assertStringContainsString(
            'Batas akses bulanan tercapai (2 sesi per bulan). Silakan coba lagi bulan depan.',
            (string) $component->getErrorBag()->first('email')
        );
    }

    public function test_monthly_budget_is_scoped_per_portfolio(): void
    {
        $first = Portfolio::where('slug', 'pdf-rag-chatbot')->first();
        $second = Portfolio::create(['slug' => 'sentiment-analyzer', 'type' => 'ai_agent', 'url' => 'http://localhost:3001']);

        foreach ([$first, $first, $second] as $project) {
            $component = new Show();
            $component->project = $project;
            $component->email = 'visitor@example.org';
            $component->requestAccess();
            $this->assertTrue($component->accessRequested);
        }

        $this->assertSame(3, AccessRequest::where('email', 'visitor@example.org')->count());
    }
}
