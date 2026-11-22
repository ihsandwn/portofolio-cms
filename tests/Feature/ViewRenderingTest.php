<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\Service;
use Tests\TestCase;
use Illuminate\Support\Facades\App;

class ViewRenderingTest extends TestCase
{
    private function makePortfolio(array $overrides = []): Portfolio
    {
        $p = new Portfolio();
        $title = $overrides['title'] ?? 'Test Project';
        $desc = $overrides['description'] ?? 'A concise description for listing cards.';
        $p->setRawAttributes([
            'title' => json_encode(['en' => $title, 'id' => $title . ' (ID)'], JSON_UNESCAPED_UNICODE),
            'description' => json_encode(['en' => $desc, 'id' => $desc . ' (ID)'], JSON_UNESCAPED_UNICODE),
            'short_description' => json_encode(['en' => $desc, 'id' => $desc . ' (ID)'], JSON_UNESCAPED_UNICODE),
            'slug' => $overrides['slug'] ?? 'test-project',
            'type' => $overrides['type'] ?? 'web',
            'completed_at' => $overrides['completed_at'] ?? null,
            'created_at' => $overrides['created_at'] ?? now()->subMonths(2),
            'updated_at' => now(),
            'url' => $overrides['url'] ?? null,
            'repo_url' => $overrides['repo_url'] ?? null,
            'client' => $overrides['client'] ?? null,
            'meta_data' => json_encode($overrides['meta_data'] ?? []),
            'tech_stack' => json_encode($overrides['tech_stack'] ?? ['Laravel', 'Vue']),
            'image' => $overrides['image'] ?? null,
            'case_study' => $overrides['case_study'] ?? null,
        ], true);
        return $p;
    }
    private function makeService(array $overrides = []): Service
    {
        $s = new Service();
        $s->setAttribute('title', $overrides['title'] ?? 'Full-Stack Development');
        $s->setAttribute('description', $overrides['description'] ?? 'Building scalable web apps with Laravel and modern frontends.');
        $s->setAttribute('icon', $overrides['icon'] ?? '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-8 4-4"></path></svg>');
        return $s;
    }

    public function testHomepageRendersEnglish(): void
    {
        App::setLocale('en');
        $portfolio1 = $this->makePortfolio(['slug' => 'proj-1', 'type' => 'web']);
        $portfolio2 = $this->makePortfolio(['slug' => 'proj-2', 'type' => 'ai_agent']);
        $services = collect([$this->makeService(), $this->makeService()]);
        $hero = [
            'hero_badge' => 'Open for work',
            'hero_title_prefix' => 'Building the',
            'hero_title_highlight' => 'Future of Intelligent Web Systems.',
            'hero_title_suffix' => '',
            'hero_subline' => 'Bridging logic and scalability.',
            'hero_description' => 'Senior full-stack developer focused on maintainable Laravel systems and practical AI features. The selected work below states scope, role, and evidence for each project.',
            'hero_cta_projects' => 'Selected Work',
            'hero_cta_linkedin' => 'LinkedIn',
            'profile_location' => 'Remote',
            'profile_availability' => 'Open for select engagements',
            'hero_philosophy' => 'Architecture is not about making final decisions, but about delaying them as long as possible. Modular, interchangeable components preserve options.',
        ];
        $techChips = ['Laravel', 'Vue', 'TypeScript', 'PostgreSQL', 'Redis', 'Docker'];

        $html = view('livewire.home', [
            'portfolios' => collect([$portfolio1, $portfolio2]),
            'services' => $services,
            'hero' => $hero,
            'aboutPage' => null,
            'aiPage' => null,
            'portfolioTotal' => 2,
            'techChips' => $techChips,
            'filter' => 'all',
        ])->render();

        $this->assertStringContainsString('Selected projects', $html);
        $this->assertStringContainsString('Building the', $html);
        $this->assertStringContainsString('Future of Intelligent Web Systems.', $html);
        $this->assertStringContainsString('Selected Work', $html);
        $this->assertStringContainsString('View Case Study', $html);
        // hero fallback description rendered via __()
        $this->assertStringContainsString('Senior full-stack developer', $html);
        // no untranslated key leakage
        $this->assertStringNotContainsString('Hero description default', $html);
    }

    public function testHomepageRendersIndonesian(): void
    {
        App::setLocale('id');

        $portfolio1 = $this->makePortfolio(['slug' => 'proj-1', 'type' => 'web']);
        $services = collect([$this->makeService()]);
        $hero = [
            'hero_badge' => 'Terbuka kerja',
            'hero_title_prefix' => 'Membangun',
            'hero_title_highlight' => 'Masa Depan Sistem Web Cerdas.',
            'hero_title_suffix' => '',
            'hero_subline' => 'Menjembatani logika dan skalabilitas.',
            'hero_description' => 'Pengembang full-stack senior yang fokus pada sistem Laravel yang mudah dirawat dan fitur AI yang praktis. Karya pilihan di bawah ini mencantumkan cakupan, peran, dan bukti tiap proyek.',
            'hero_cta_projects' => 'Karya Pilihan',
            'hero_cta_linkedin' => 'LinkedIn',
            'profile_location' => 'Remote',
            'profile_availability' => 'Terbuka untuk proyek tertentu',
            'hero_philosophy' => 'Arsitektur bukan tentang keputusan akhir, melainkan menundanya selama mungkin. Komponen modular dan dapat diganti menjaga opsi tetap terbuka.',
        ];
        $techChips = ['Laravel', 'Vue'];

        $html = view('livewire.home', [
            'portfolios' => collect([$portfolio1]),
            'services' => $services,
            'hero' => $hero,
            'aboutPage' => null,
            'aiPage' => null,
            'portfolioTotal' => 1,
            'techChips' => $techChips,
            'filter' => 'all',
        ])->render();

        $this->assertStringContainsString('Proyek pilihan', $html);
        $this->assertStringContainsString('Membangun', $html);
        $this->assertStringContainsString('Masa Depan Sistem Web Cerdas.', $html);
        $this->assertStringContainsString('Karya Pilihan', $html);
        $this->assertStringContainsString('Lihat Studi Kasus', $html);
        $this->assertStringContainsString('Pengembang full-stack senior', $html);
        $this->assertStringNotContainsString('Hero description default', $html);

        App::setLocale('en');
    }

    public function testPortfolioShowRendersEnglish(): void
    {
        App::setLocale('en');
        $p = $this->makePortfolio([
            'slug' => 'portfolio-demo',
            'type' => 'web',
            'client' => 'Acme Corp',
            'completed_at' => now()->subMonth(),
            'meta_data' => ['role' => 'Lead Architect'],
            'tech_stack' => ['Laravel', 'PostgreSQL', 'Tailwind'],
            'case_study' => null,
        ]);

        $html = view('livewire.public.portfolio.show', ['portfolio' => $p])->render();

        $this->assertStringContainsString('Context', $html);
        $this->assertStringContainsString('Acme Corp', $html);
        $this->assertStringContainsString('Lead Architect', $html);
        $this->assertStringContainsString('Approach and evidence', $html);
        $this->assertStringContainsString('Case study not documented yet. Measured outcomes are listed only when documented.', $html);
        $this->assertStringContainsString('Tech Stack', $html);
    }

    public function testPortfolioShowRendersIndonesian(): void
    {
        App::setLocale('id');
        $p = $this->makePortfolio([
            'slug' => 'portfolio-demo-id',
            'client' => 'Acme Corp',
            'meta_data' => ['role' => 'Arsitek Utama'],
        ]);

        $html = view('livewire.public.portfolio.show', ['portfolio' => $p])->render();

        $this->assertStringContainsString('Konteks', $html);
        $this->assertStringContainsString('Acme Corp', $html);
        $this->assertStringContainsString('Arsitek Utama', $html);
        $this->assertStringContainsString('Pendekatan dan bukti', $html);
        $this->assertStringContainsString('Studi kasus belum didokumentasikan. Hasil terukur hanya dicantumkan jika ada dokumentasinya.', $html);

        App::setLocale('en');
    }

    public function testAiLabIndexRendersEnglish(): void
    {
        App::setLocale('en');
        $p1 = $this->makePortfolio(['slug' => 'ai-1', 'type' => 'ai_agent']);
        $p2 = $this->makePortfolio(['slug' => 'ai-2', 'type' => 'ai_agent']);

        $projects = new \Illuminate\Pagination\LengthAwarePaginator([$p1, $p2], 2, 15);
        $html = view('livewire.public.ai-lab.index', ['projects' => $projects])->render();

        $this->assertStringContainsString('AI Laboratory', $html);
        $this->assertStringContainsString('Experimental Zone', $html);
        $this->assertStringContainsString('See Demonstration', $html);
        $this->assertStringContainsString('These are experimental demos, not production services.', $html);
    }

    public function testAiLabShowRendersEnglish(): void
    {
        App::setLocale('en');
        $p = $this->makePortfolio([
            'slug' => 'hr-screening',
            'type' => 'ai_agent',
            'url' => 'https://example.com/hr-screening',
            'tech_stack' => ['Laravel', 'Groq', 'Inertia'],
        ]);

        $html = view('livewire.public.ai-lab.show', [
            'project' => $p,
            'accessRequested' => false,
            'generatedUrl' => null,
            'errors' => new \Illuminate\Support\ViewErrorBag(),
        ])->render();

        $this->assertStringContainsString('Demonstration / Case Study', $html);
        $this->assertStringContainsString('Demo notes', $html);
        $this->assertStringContainsString('Intended use', $html);
        $this->assertStringContainsString('Demonstrates an experimental AI workflow', $html);
        $this->assertStringContainsString('Input handling', $html);
        $this->assertStringContainsString('Input is sent to third-party AI providers', $html);
        $this->assertStringContainsString('Output handling', $html);
        $this->assertStringContainsString('Responses come from a language model', $html);
        $this->assertStringContainsString('Limitations', $html);
        $this->assertStringContainsString('Small demo scope with no formal evaluation', $html);
        $this->assertStringContainsString('Privacy &amp; retention', $html);
        $this->assertStringContainsString('An access grant is recorded with the email and IP address', $html);
        $this->assertStringContainsString('Email address', $html);
        $this->assertStringContainsString('Enter your email to access', $html);
        $this->assertStringContainsString('Request Access', $html);
        $this->assertStringContainsString('Your email is stored only to record this demo access request', $html);
    }

    public function testAiLabShowRendersIndonesian(): void
    {
        App::setLocale('id');
        $p = $this->makePortfolio([
            'slug' => 'hr-screening-id',
            'type' => 'ai_agent',
            'url' => 'https://example.com/hr-screening',
        ]);

        $html = view('livewire.public.ai-lab.show', [
            'project' => $p,
            'accessRequested' => false,
            'generatedUrl' => null,
            'errors' => new \Illuminate\Support\ViewErrorBag(),
        ])->render();

        $this->assertStringContainsString('Demonstrasi / Studi Kasus', $html);
        $this->assertStringContainsString('Catatan demo', $html);
        $this->assertStringContainsString('Tujuan penggunaan', $html);
        $this->assertStringContainsString('Mendemonstrasikan alur kerja AI eksperimental', $html);
        $this->assertStringContainsString('Penanganan input', $html);
        $this->assertStringContainsString('Input dikirim ke penyedia AI pihak ketiga', $html);
        $this->assertStringContainsString('Penanganan output', $html);
        $this->assertStringContainsString('Respons berasal dari model bahasa', $html);
        $this->assertStringContainsString('Keterbatasan', $html);
        $this->assertStringContainsString('Cakupan demo kecil tanpa evaluasi formal', $html);
        $this->assertStringContainsString('Privasi &amp; retensi', $html);
        $this->assertStringContainsString('Izin akses dicatat bersama email dan alamat IP', $html);
        $this->assertStringContainsString('Alamat email', $html);
        $this->assertStringContainsString('Masukkan email Anda untuk mengakses', $html);
        $this->assertStringContainsString('Minta Akses', $html);
        $this->assertStringContainsString('Email Anda hanya disimpan untuk mencatat permintaan akses demo ini', $html);

        App::setLocale('en');
    }
}