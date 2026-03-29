@php
    use Illuminate\Support\Str;
    $aboutPlain = $aboutPage && $aboutPage->content
        ? trim(preg_replace('/\s+/', ' ', strip_tags($aboutPage->content)))
        : '';
    $philosophyQuote = $hero['hero_philosophy'] ?? null;
    if (! $philosophyQuote && $aboutPlain !== '') {
        $philosophyQuote = Str::limit($aboutPlain, 280);
    }
    $philosophyQuote = $philosophyQuote ?: __('Philosophy fallback quote');
    $heroSubline = $hero['hero_subline'] ?? __('Hero subline default');
@endphp
<div>
    <section class="relative pt-28 pb-12 md:pt-32 md:pb-16" aria-labelledby="hero-heading">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-12 gap-y-12 md:gap-x-12 items-start mb-20 md:mb-28">
                <div class="col-span-12 md:col-span-7">
                    <div class="mb-4 inline-flex items-center gap-2">
                        <span class="h-px w-8 bg-primary" aria-hidden="true"></span>
                        <p class="font-label text-[10px] uppercase tracking-[0.2em] text-primary font-bold">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-primary animate-pulse" aria-hidden="true"></span>
                                {{ $hero['hero_badge'] ?? __('Contact Me') }}
                            </span>
                        </p>
                    </div>
                    <h1 id="hero-heading" class="font-headline font-extrabold text-4xl md:text-[48px] leading-[1.1] tracking-tighter text-on-background mb-8">
                        <span class="block">
                            {{ $hero['hero_title_prefix'] ?? 'Building the' }}
                            @if(!empty($hero['hero_title_highlight']))
                                <span class="text-primary">{{ $hero['hero_title_highlight'] }}</span>
                            @endif
                            {{ $hero['hero_title_suffix'] ?? 'Future of Intelligent Web Systems.' }}
                        </span>
                        <span class="block text-outline mt-2">{{ $heroSubline }}</span>
                    </h1>
                    <p class="max-w-md text-[13px] leading-relaxed text-secondary mb-10">
                        {{ $hero['hero_description'] ?? 'Senior Full-Stack Architect specializing in enterprise systems, AI agents, and scalable architecture.' }}
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#portfolio" class="inline-flex items-center justify-center gap-2 bg-primary text-on-primary px-6 py-3 text-[11px] font-label font-bold uppercase tracking-widest hover:bg-primary-dim transition-colors duration-blueprint">
                            {{ $hero['hero_cta_projects'] ?? __('Selected Work') }}
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <a href="https://www.linkedin.com/in/ichsan-dwi-nugraha-694a6a114" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 border border-outline px-6 py-3 text-[11px] font-label font-bold uppercase tracking-widest text-on-background hover:bg-surface-container transition-colors duration-blueprint">
                            {{ $hero['hero_cta_cv'] ?? 'View CV' }}
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-5 relative aspect-square border border-outline-variant/30 flex items-center justify-center p-8 blueprint-grid bg-surface-container-lowest/40">
                    <div class="absolute top-0 left-0 w-4 h-4 border-t border-l border-primary pointer-events-none" aria-hidden="true"></div>
                    <div class="absolute bottom-0 right-0 w-4 h-4 border-b border-r border-primary pointer-events-none" aria-hidden="true"></div>
                    <svg class="w-full h-full max-w-[min(100%,220px)] text-outline-variant/60" fill="none" stroke="currentColor" stroke-width="0.5" viewBox="0 0 200 200" aria-hidden="true">
                        <rect height="120" width="120" x="40" y="40"></rect>
                        <circle cx="100" cy="100" r="40"></circle>
                        <line x1="40" x2="160" y1="40" y2="160"></line>
                        <line x1="160" x2="40" y1="40" y2="160"></line>
                        <path d="M20,100 L180,100" stroke-dasharray="4,4"></path>
                        <path d="M100,20 L100,180" stroke-dasharray="4,4"></path>
                        <rect class="text-primary" height="30" stroke-width="1" width="30" x="85" y="85"></rect>
                        <circle cx="40" cy="40" fill="currentColor" r="3"></circle>
                        <circle cx="160" cy="40" fill="currentColor" r="3"></circle>
                        <circle cx="40" cy="160" fill="currentColor" r="3"></circle>
                        <circle cx="160" cy="160" fill="currentColor" r="3"></circle>
                    </svg>
                    <div class="absolute bottom-4 left-4 font-label text-[9px] text-outline uppercase tracking-tighter">
                        Ref: FIG_01 // SYSTEM_TOPOLOGY
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="pb-20 md:pb-28 border-t border-outline-variant/20" aria-labelledby="expertise-heading">
        <div class="max-w-7xl mx-auto px-6 pt-16 md:pt-20">
            <div class="flex justify-between items-end mb-12 border-b border-outline-variant/20 pb-4">
                <h2 id="expertise-heading" class="font-label text-xs font-bold uppercase tracking-[0.3em] text-on-background">{{ __('Core Expertise') }}</h2>
                <span class="font-label text-[10px] text-outline">{{ __('Technical specification') }}</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-px bg-outline-variant/20 border border-outline-variant/20">
                @foreach($services as $service)
                    <article class="bg-surface p-8 group hover:bg-surface-container-lowest transition-colors duration-blueprint">
                        <div class="text-primary mb-4 [&_svg]:w-[18px] [&_svg]:h-[18px] [&_svg]:shrink-0">
                            {!! $service->icon ?? '' !!}
                        </div>
                        <h3 class="font-headline font-bold text-sm mb-2 uppercase tracking-tight text-on-background">{{ $service->title }}</h3>
                        <p class="text-[11px] text-secondary leading-relaxed">{{ $service->description }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="about" class="border-t border-outline-variant/30 bg-surface-container-low/40" aria-labelledby="about-heading">
        <div class="max-w-7xl mx-auto px-6 py-16 md:py-20">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 border-b border-outline-variant/20 pb-16 mb-12">
                <div class="md:col-span-4">
                    <h2 class="font-label text-xs font-bold uppercase tracking-[0.3em] text-primary mb-8">{{ __('Architect profile') }}</h2>
                    <div class="space-y-6">
                        <div>
                            <span class="font-label text-[9px] text-outline block uppercase mb-1">{{ __('Location') }}</span>
                            <span class="text-sm font-medium text-on-background">{{ $hero['profile_location'] ?? __('Profile location default') }}</span>
                        </div>
                        <div>
                            <span class="font-label text-[9px] text-outline block uppercase mb-1">{{ __('Availability') }}</span>
                            <span class="text-sm font-medium text-on-background">{{ $hero['profile_availability'] ?? __('Profile availability default') }}</span>
                        </div>
                        <div>
                            <span class="font-label text-[9px] text-outline block uppercase mb-1">{{ __('Total systems shipped') }}</span>
                            <span class="text-sm font-medium text-on-background mono-num">{{ $portfolioTotal }}</span>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-surface-container p-6 border-l-2 border-primary">
                        <h3 class="font-label text-[10px] font-bold uppercase mb-4 text-on-background">{{ __('Adaptive philosophy') }}</h3>
                        <p class="text-[12px] leading-relaxed text-secondary italic">
                            &ldquo;{{ $philosophyQuote }}&rdquo;
                        </p>
                    </div>
                    <div class="bg-surface-container p-6 border-l-2 border-outline">
                        <h3 class="font-label text-[10px] font-bold uppercase mb-4 text-on-background">{{ __('Tech stack (partial)') }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @forelse($techChips as $chip)
                                <span class="bg-surface-container-lowest px-2 py-1 text-[9px] font-label border border-outline-variant/30 text-on-background">{{ $chip }}</span>
                            @empty
                                <span class="text-[11px] text-secondary">{{ __('No tech tags yet') }}</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            @if($aboutPage)
                <h2 id="about-heading" class="font-headline text-2xl md:text-3xl font-bold text-on-background mb-8">{{ $aboutPage->title ?? __('About Me') }}</h2>
                <div class="content-section text-secondary space-y-4 max-w-3xl">
                    @safeHtml($aboutPage->content ?? '')
                </div>
            @endif
        </div>
    </section>

    <section id="portfolio" class="py-20 md:py-28 bg-surface-container-low border-t border-outline-variant/20" aria-labelledby="portfolio-heading">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
                <div>
                    <h2 id="portfolio-heading" class="font-headline text-2xl md:text-3xl font-bold text-on-background mb-2">{{ __('Selected Work') }}</h2>
                    <p class="text-secondary text-sm max-w-xl">{{ __('Selected Work Description') }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" wire:click="setFilter('all')" class="px-4 py-1.5 font-label text-[10px] tracking-widest uppercase transition-colors duration-blueprint {{ $filter === 'all' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-secondary hover:bg-outline-variant hover:text-on-background' }}">
                        {{ __('All') }}
                    </button>
                    <button type="button" wire:click="setFilter('web')" class="px-4 py-1.5 font-label text-[10px] tracking-widest uppercase transition-colors duration-blueprint {{ $filter === 'web' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-secondary hover:bg-outline-variant hover:text-on-background' }}">
                        {{ __('Web Dev') }}
                    </button>
                    <button type="button" wire:click="setFilter('ai')" class="px-4 py-1.5 font-label text-[10px] tracking-widest uppercase transition-colors duration-blueprint {{ $filter === 'ai' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-secondary hover:bg-outline-variant hover:text-on-background' }}">
                        {{ __('AI Solutions') }}
                    </button>
                </div>
            </div>

            <div wire:loading wire:target="setFilter" class="w-full overflow-x-auto border border-outline-variant/20" aria-hidden="true">
                <table class="w-full text-left border-collapse min-w-[640px]">
                    <tbody class="divide-y divide-outline-variant/10">
                        @foreach(range(1, 4) as $_)
                            <tr>
                                <td class="py-6 px-4 w-24"><div class="h-3 bg-surface-container-high animate-pulse rounded-sm w-16"></div></td>
                                <td class="py-6 px-4"><div class="h-4 bg-surface-container-high animate-pulse rounded-sm w-48"></div></td>
                                <td class="py-6 px-4 hidden lg:table-cell"><div class="h-3 bg-surface-container animate-pulse rounded-sm w-full max-w-xs"></div></td>
                                <td class="py-6 px-4 hidden md:table-cell w-32"><div class="h-3 bg-surface-container animate-pulse rounded-sm w-24"></div></td>
                                <td class="py-6 px-4 text-right w-28"><div class="h-3 bg-surface-container-high animate-pulse rounded-sm w-20 ml-auto"></div></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div wire:loading.remove wire:target="setFilter" class="w-full overflow-x-auto border border-outline-variant/20 bg-surface-container-lowest">
                <table class="w-full text-left border-collapse min-w-[640px]">
                    <thead>
                        <tr class="border-b border-outline-variant/30 text-secondary font-label text-[10px] tracking-widest uppercase">
                            <th class="pb-4 pt-4 font-medium px-4">{{ __('Project UID') }}</th>
                            <th class="pb-4 pt-4 font-medium px-4">{{ __('Project name') }}</th>
                            <th class="pb-4 pt-4 font-medium px-4 hidden lg:table-cell">{{ __('Tech stack') }}</th>
                            <th class="pb-4 pt-4 font-medium px-4 hidden md:table-cell">{{ __('Role') }}</th>
                            <th class="pb-4 pt-4 font-medium px-4 text-right">{{ __('Reference') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @foreach($portfolios as $portfolio)
                            @php
                                $href = $portfolio->type === 'ai_agent' ? route('ai-lab.show', $portfolio->slug) : route('portfolio.show', $portfolio->slug);
                                $uid = $portfolio->completed_at
                                    ? $portfolio->completed_at->format('Y.m')
                                    : $portfolio->created_at->format('Y.m');
                                $role = data_get($portfolio->meta_data, 'role')
                                    ?? ($portfolio->type === 'ai_agent' ? __('AI Solutions') : __('Development'));
                            @endphp
                            <tr class="group hover:bg-surface-container-low transition-colors duration-blueprint">
                                <td class="py-6 md:py-8 px-4 font-label text-[11px] text-outline mono-num align-top">{{ $uid }}</td>
                                <td class="py-6 md:py-8 px-4 align-top">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 shrink-0 bg-surface-container-highest flex items-center justify-center border border-outline-variant/20 text-outline">
                                            @if($portfolio->type === 'ai_agent')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-headline font-bold text-on-background tracking-tight">{{ $portfolio->title }}</div>
                                            <div class="lg:hidden mt-2 flex flex-wrap gap-2">
                                                @foreach(collect($portfolio->tech_stack)->take(4) as $tech)
                                                    <span class="font-label text-[9px] text-primary">{{ is_string($tech) ? strtoupper($tech) : $tech }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 md:py-8 px-4 hidden lg:table-cell align-top">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(collect($portfolio->tech_stack ?? [])->take(6) as $tech)
                                            <span class="px-2 py-0.5 bg-surface-container-high text-on-surface-variant font-label text-[10px]">{{ is_string($tech) ? strtoupper($tech) : $tech }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-6 md:py-8 px-4 hidden md:table-cell align-top">
                                    <span class="font-body text-xs text-secondary italic">{{ $role }}</span>
                                </td>
                                <td class="py-6 md:py-8 px-4 text-right align-top">
                                    <a href="{{ $href }}" wire:navigate class="inline-flex items-center gap-1 text-primary hover:text-primary-dim transition-colors group/link font-label text-[10px] tracking-widest uppercase">
                                        {{ __('View Case Study') }}
                                        <svg class="w-3.5 h-3.5 shrink-0 transform group-hover/link:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M7 7h10v10"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section id="ai-lab" class="py-20 md:py-28 border-t border-outline-variant/20" aria-labelledby="ailab-heading">
        <div class="max-w-7xl mx-auto px-6">
            <div class="border border-outline-variant/20 bg-surface-container-lowest p-8 md:p-12">
                <p class="font-label text-[10px] uppercase tracking-[0.2em] text-primary font-bold mb-6">{{ __('Experimental') }}</p>
                <h2 id="ailab-heading" class="font-headline text-2xl md:text-3xl font-bold text-on-background mb-6">{{ $aiPage->title ?? __('AI Lab') }}</h2>
                <div class="content-section text-secondary space-y-4 mb-10">
                    @safeHtml($aiPage->content ?? '')
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 p-6 bg-surface-container-low border border-outline-variant/20">
                    <div>
                        <h3 class="text-xl font-headline font-semibold text-on-background mb-1">{{ __('CTA Heading') }}</h3>
                        <p class="text-secondary text-sm">{{ __('CTA Description') }}</p>
                    </div>
                    <a href="mailto:ichsanworkthings@gmail.com" class="shrink-0 inline-flex items-center justify-center px-6 py-3 bg-primary text-on-primary font-label text-[10px] tracking-widest uppercase hover:bg-primary-dim transition-colors duration-blueprint">
                        {{ __('Start a Conversation') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
