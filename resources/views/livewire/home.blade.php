<div>
    {{-- Hero: Clear, direct value proposition for senior fullstack developer --}}
    <section class="relative pt-28 pb-20 md:pt-36 md:pb-28" aria-labelledby="hero-heading">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-400 text-sm font-medium mb-6">
                    <span class="w-2 h-2 rounded-full bg-sky-400 animate-pulse" aria-hidden="true"></span>
                    {{ $hero['hero_badge'] ?? __('Contact Me') }}
                </p>
                <h1 id="hero-heading" class="text-4xl sm:text-5xl md:text-6xl font-bold text-white tracking-tight mb-6 leading-[1.1]">
                    {{ $hero['hero_title_prefix'] ?? 'Building the' }}
                    <span class="text-sky-400">{{ $hero['hero_title_highlight'] ?? 'Future' }}</span>
                    {{ $hero['hero_title_suffix'] ?? 'of Intelligent Web Systems.' }}
                </h1>
                <p class="text-lg md:text-xl text-slate-400 mb-10 leading-relaxed">
                    {{ $hero['hero_description'] ?? 'Senior Full-Stack Architect specializing in enterprise systems, AI agents, and scalable architecture.' }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#portfolio" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition">
                        {{ $hero['hero_cta_projects'] ?? __('Selected Work') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </a>
                    <a href="https://www.linkedin.com/in/ichsan-dwi-nugraha-694a6a114" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-medium border border-slate-600 text-slate-300 hover:bg-slate-800/50 hover:border-slate-500 transition">
                        {{ $hero['hero_cta_cv'] ?? 'View CV' }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- About: CMS-driven, clean layout --}}
    <section id="about" class="py-20 md:py-28 bg-slate-900/30" aria-labelledby="about-heading">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h2 id="about-heading" class="text-2xl md:text-3xl font-bold text-white mb-8">{{ $aboutPage->title ?? __('About Me') }}</h2>
                <div class="content-section text-slate-400 space-y-4">
                    @safeHtml($aboutPage->content ?? '')
                </div>
            </div>
        </div>
    </section>

    {{-- Expertise: Scannable grid --}}
    <section id="services" class="py-20 md:py-28 border-t border-slate-800/80" aria-labelledby="expertise-heading">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 id="expertise-heading" class="text-2xl md:text-3xl font-bold text-white mb-12">{{ __('Core Expertise') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($services as $service)
                <article class="p-6 rounded-xl border border-slate-800/80 bg-slate-900/30 hover:border-slate-700 transition">
                    <div class="w-10 h-10 rounded-lg bg-sky-500/10 flex items-center justify-center text-sky-400 mb-5">
                        {!! $service->icon ?? '' !!}
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">{{ $service->title }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">{{ $service->description }}</p>
                </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Portfolio: Featured work with clear filter --}}
    <section id="portfolio" class="py-20 md:py-28 bg-slate-900/30 border-t border-slate-800/80" aria-labelledby="portfolio-heading">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
                <div>
                    <h2 id="portfolio-heading" class="text-2xl md:text-3xl font-bold text-white mb-2">{{ __('Selected Work') }}</h2>
                    <p class="text-slate-400 max-w-xl">{{ __('Selected Work Description') }}</p>
                </div>
                <div class="flex gap-2 p-1 rounded-lg bg-slate-800/50 border border-slate-700/50 w-fit">
                    <button wire:click="setFilter('all')" class="px-4 py-2 rounded-md text-sm font-medium transition {{ $filter === 'all' ? 'bg-slate-700 text-white' : 'text-slate-400 hover:text-white' }}">
                        {{ __('All') }}
                    </button>
                    <button wire:click="setFilter('web')" class="px-4 py-2 rounded-md text-sm font-medium transition {{ $filter === 'web' ? 'bg-slate-700 text-white' : 'text-slate-400 hover:text-white' }}">
                        {{ __('Web Dev') }}
                    </button>
                    <button wire:click="setFilter('ai')" class="px-4 py-2 rounded-md text-sm font-medium transition {{ $filter === 'ai' ? 'bg-sky-900/50 text-sky-300 border border-sky-500/30' : 'text-slate-400 hover:text-white' }}">
                        {{ __('AI Solutions') }}
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($portfolios as $portfolio)
                <article class="group rounded-xl border border-slate-800/80 bg-slate-900/50 overflow-hidden hover:border-slate-700 transition flex flex-col h-full">
                    <div class="h-40 bg-gradient-to-br {{ $portfolio->type === 'ai_agent' ? 'from-slate-800 to-sky-900/30' : 'from-slate-800 to-slate-700' }} flex items-center justify-center relative">
                        <span class="text-5xl font-bold text-white/10 group-hover:scale-110 transition" aria-hidden="true">{{ substr($portfolio->title, 0, 1) }}</span>
                        @if($portfolio->type === 'ai_agent')
                        <span class="absolute top-3 right-3 px-2 py-1 rounded bg-sky-500/20 text-sky-300 text-xs font-medium">
                            {{ __('AI Solutions') }}
                        </span>
                        @endif
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="text-lg font-semibold text-white mb-2 group-hover:text-sky-400 transition">{{ $portfolio->title }}</h3>
                        <p class="text-slate-400 text-sm line-clamp-3 flex-1">{{ $portfolio->description }}</p>
                        <a href="{{ $portfolio->type === 'ai_agent' ? route('ai-lab.show', $portfolio->slug) : route('portfolio.show', $portfolio->slug) }}" class="inline-flex items-center gap-1.5 mt-4 text-sm font-medium text-sky-400 hover:text-sky-300 transition">
                            {{ __('View Case Study') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- AI Lab: CMS content, simplified CTA --}}
    <section id="ai-lab" class="py-20 md:py-28 border-t border-slate-800/80" aria-labelledby="ailab-heading">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-slate-800/80 bg-slate-900/50 p-8 md:p-12">
                <p class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-400 text-sm font-medium mb-6">
                    {{ __('Experimental') }}
                </p>
                <h2 id="ailab-heading" class="text-2xl md:text-3xl font-bold text-white mb-6">{{ $aiPage->title ?? __('AI Lab') }}</h2>
                <div class="content-section text-slate-400 space-y-4 mb-10">
                    @safeHtml($aiPage->content ?? '')
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 p-6 rounded-xl bg-slate-800/30 border border-slate-700/50">
                    <div>
                        <h3 class="text-xl font-semibold text-white mb-1">{{ __('CTA Heading') }}</h3>
                        <p class="text-slate-400 text-sm">{{ __('CTA Description') }}</p>
                    </div>
                    <a href="mailto:ichsanworkthings@gmail.com" class="shrink-0 inline-flex items-center justify-center px-6 py-3 bg-white text-slate-900 font-semibold rounded-lg hover:bg-slate-100 transition">
                        {{ __('Start a Conversation') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
