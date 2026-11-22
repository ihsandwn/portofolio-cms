<div class="max-w-4xl mx-auto pt-32 py-12 px-4 sm:px-6 lg:px-10">
     <nav class="flex mb-8 font-label text-[11px] uppercase tracking-wider text-secondary" aria-label="{{ __('Breadcrumb') }}">
        <ol class="inline-flex items-center flex-wrap gap-x-2 gap-y-1">
            <li class="inline-flex items-center">
                <a href="/" wire:navigate class="hover:text-primary transition-colors duration-blueprint">{{ __('Home') }}</a>
            </li>
            <li><span class="text-outline-variant">/</span></li>
            <li class="inline-flex items-center">
                <a href="{{ route('portfolio.index') }}" wire:navigate class="hover:text-primary transition-colors duration-blueprint">{{ __('Portfolio') }}</a>
            </li>
            <li><span class="text-outline-variant">/</span></li>
            <li aria-current="page">
                <span class="text-on-background font-headline font-medium truncate max-w-[200px] sm:max-w-md block">{{ $portfolio->title }}</span>
            </li>
        </ol>
    </nav>

    <header class="mb-12">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-6">
            <div>
                @if($portfolio->type)
                <p class="font-label text-[11px] uppercase tracking-[0.2em] text-primary font-bold mb-3">{{ $portfolio->type }}</p>
                @endif
                <h1 class="font-headline text-4xl md:text-5xl font-bold text-on-background">{{ $portfolio->title }}</h1>
            </div>
            <div class="flex flex-wrap gap-3 shrink-0">
                @if($portfolio->url)
                <a href="{{ $portfolio->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-11 items-center px-4 py-2 bg-primary text-on-primary font-label text-[11px] tracking-widest uppercase hover:bg-primary-dim transition-colors duration-blueprint">
                    {{ __('Visit Site') }} <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
                @endif
                @if($portfolio->repo_url)
                <a href="{{ $portfolio->repo_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-11 items-center px-4 py-2 border border-outline text-on-background font-label text-[11px] tracking-widest uppercase hover:bg-surface-container transition-colors duration-blueprint">
                    {{ __('View Code') }}
                </a>
                @endif
            </div>
        </div>

        <div class="flex flex-wrap gap-3 mb-8">
            <span class="px-3 py-1 bg-surface-container-high border border-outline-variant/20 text-secondary font-label text-[11px] uppercase tracking-wider">{{ __('Client') }}: {{ $portfolio->client ?? __('Confidential') }}</span>
            <span class="px-3 py-1 bg-surface-container-high border border-outline-variant/20 text-secondary font-label text-[11px] uppercase tracking-wider">{{ __('Completed') }}: {{ $portfolio->completed_at?->format('F Y') ?? __('Ongoing') }}</span>
        </div>

        <div class="bg-surface-container-low p-6 border border-outline-variant/20">
             <h2 class="font-label text-[11px] font-semibold text-primary uppercase tracking-[0.2em] mb-4">{{ __('Tech Stack') }}</h2>
             <div class="flex flex-wrap gap-2">
                @foreach($portfolio->tech_stack ?? [] as $tech)
                <span class="px-3 py-1 bg-primary-container/50 text-on-primary-container font-label text-[11px] uppercase tracking-wider border border-outline-variant/20">{{ $tech }}</span>
                @endforeach
            </div>
        </div>
    </header>

    <section class="bg-surface-container-low p-6 border border-outline-variant/20 mb-8" aria-labelledby="summary-heading">
        <h2 id="summary-heading" class="font-label text-[11px] font-semibold text-primary uppercase tracking-[0.2em] mb-3">{{ __('Summary') }}</h2>
        <p class="text-lg text-secondary leading-relaxed">{{ trim((string) $portfolio->description) ?: __('Summary not available.') }}</p>
    </section>

    <section class="mb-12" aria-labelledby="context-heading">
        <h2 id="context-heading" class="font-headline text-2xl font-bold text-on-background mb-4">{{ __('Context') }}</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-surface-container-low p-5 border border-outline-variant/20"><dt class="font-label text-[11px] uppercase tracking-widest text-primary mb-2">{{ __('Client') }}</dt><dd class="text-sm text-on-background">{{ $portfolio->client ?? __('Confidential') }}</dd></div>
            <div class="bg-surface-container-low p-5 border border-outline-variant/20"><dt class="font-label text-[11px] uppercase tracking-widest text-primary mb-2">{{ __('Role') }}</dt><dd class="text-sm text-on-background">{{ data_get($portfolio->meta_data, 'role') ?? __('Contribution details in case study') }}</dd></div>
            <div class="bg-surface-container-low p-5 border border-outline-variant/20"><dt class="font-label text-[11px] uppercase tracking-widest text-primary mb-2">{{ __('Project type') }}</dt><dd class="text-sm text-on-background">{{ $portfolio->type ?? __('Not specified') }}</dd></div>
            <div class="bg-surface-container-low p-5 border border-outline-variant/20"><dt class="font-label text-[11px] uppercase tracking-widest text-primary mb-2">{{ __('Status') }}</dt><dd class="text-sm text-on-background">{{ $portfolio->completed_at ? __('Completed') : __('Ongoing') }}</dd></div>
        </dl>
    </section>

    <section aria-labelledby="evidence-heading">
        <h2 id="evidence-heading" class="font-headline text-2xl font-bold text-on-background mb-4">{{ __('Approach and evidence') }}</h2>
        @if($portfolio->case_study)
            <article class="prose-blueprint">
                @safeHtml(Str::markdown($portfolio->case_study))
            </article>
        @else
            <p class="text-secondary">{{ __('Case study not documented yet. Measured outcomes are listed only when documented.') }}</p>
        @endif
    </section>

    <section class="mt-12 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 p-6 bg-surface-container-low border border-outline-variant/20" aria-labelledby="contact-heading">
        <div>
            <h2 id="contact-heading" class="text-xl font-headline font-semibold text-on-background mb-1">{{ __('CTA Heading') }}</h2>
            <p class="text-secondary text-sm">{{ __('CTA Description') }}</p>
        </div>
        <a href="mailto:ichsanworkthings@gmail.com" class="shrink-0 inline-flex min-h-11 items-center justify-center px-6 py-3 bg-primary text-on-primary font-label text-[11px] tracking-widest uppercase hover:bg-primary-dim transition-colors duration-blueprint">
            {{ __('Start a Conversation') }}
        </a>
    </section>
</div>
