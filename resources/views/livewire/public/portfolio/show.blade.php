<div class="max-w-4xl mx-auto pt-32 py-12 px-4 sm:px-6 lg:px-10">
     <nav class="flex mb-8 font-label text-[10px] uppercase tracking-wider text-secondary" aria-label="Breadcrumb">
        <ol class="inline-flex items-center flex-wrap gap-x-2 gap-y-1">
            <li class="inline-flex items-center">
                <a href="/" wire:navigate class="hover:text-primary transition-colors duration-blueprint">Home</a>
            </li>
            <li><span class="text-outline-variant">/</span></li>
            <li class="inline-flex items-center">
                <a href="{{ route('portfolio.index') }}" wire:navigate class="hover:text-primary transition-colors duration-blueprint">Portfolio</a>
            </li>
            <li><span class="text-outline-variant">/</span></li>
            <li aria-current="page">
                <span class="text-on-background font-headline font-medium truncate max-w-[200px] sm:max-w-md block">{{ $portfolio->title }}</span>
            </li>
        </ol>
    </nav>

    <header class="mb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-6">
            <h1 class="font-headline text-4xl md:text-5xl font-bold text-on-background">{{ $portfolio->title }}</h1>
            @if($portfolio->url)
            <a href="{{ $portfolio->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-primary text-on-primary font-label text-[10px] tracking-widest uppercase hover:bg-primary-dim transition-colors duration-blueprint shrink-0">
                Visit Site <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
            @endif
        </div>

        <div class="flex flex-wrap gap-3 mb-8">
            <span class="px-3 py-1 bg-surface-container-high border border-outline-variant/20 text-secondary font-label text-[10px] uppercase tracking-wider">{{ __('Client') }}: {{ $portfolio->client ?? 'Confidential' }}</span>
            <span class="px-3 py-1 bg-surface-container-high border border-outline-variant/20 text-secondary font-label text-[10px] uppercase tracking-wider">{{ __('Completed') }}: {{ $portfolio->completed_at?->format('F Y') ?? 'Ongoing' }}</span>
        </div>

        <div class="bg-surface-container-low p-6 border border-outline-variant/20">
             <h3 class="font-label text-[10px] font-semibold text-primary uppercase tracking-[0.2em] mb-4">{{ __('Tech Stack') }}</h3>
             <div class="flex flex-wrap gap-2">
                @foreach($portfolio->tech_stack ?? [] as $tech)
                <span class="px-3 py-1 bg-primary-container/50 text-on-primary-container font-label text-[10px] uppercase tracking-wider border border-outline-variant/20">{{ $tech }}</span>
                @endforeach
            </div>
        </div>
    </header>

    <article class="prose-blueprint">
        @safeHtml(Str::markdown($portfolio->case_study ?? ''))
    </article>
</div>
