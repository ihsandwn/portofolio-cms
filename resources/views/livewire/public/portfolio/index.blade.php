<div class="max-w-7xl mx-auto pt-28 md:pt-32 pb-24 px-6">
    <header class="mb-12 md:mb-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="font-headline text-3xl md:text-4xl font-bold text-on-background mb-4">{{ __('Selected Work') }}</h1>
                <p class="text-sm md:text-base text-secondary max-w-xl leading-relaxed">{{ __('Selected Work Description') }}</p>
            </div>
        </div>
    </header>

    <div class="border border-outline-variant/20 bg-surface-container-lowest divide-y divide-outline-variant/10">
        @foreach($portfolios as $portfolio)
            @php
                $href = $portfolio->type === 'ai_agent' ? route('ai-lab.show', $portfolio->slug) : route('portfolio.show', $portfolio->slug);
                $uid = $portfolio->completed_at
                    ? $portfolio->completed_at->format('Y.m')
                    : $portfolio->created_at->format('Y.m');
                $role = data_get($portfolio->meta_data, 'role')
                    ?? ($portfolio->type === 'ai_agent' ? __('AI Solutions') : __('Development'));
            @endphp
            <article class="group p-5 md:p-6 hover:bg-surface-container-low transition-colors duration-blueprint">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:gap-6 md:items-start">
                    <div class="md:col-span-2 font-label text-xs text-secondary mono-num">{{ $uid }}</div>
                    <div class="md:col-span-7 min-w-0">
                        <div class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-surface-container-highest flex items-center justify-center border border-outline-variant/20 overflow-hidden">
                                    @if($portfolio->image)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($portfolio->image) }}" alt="" class="w-full h-full object-cover">
                                    @elseif($portfolio->type === 'ai_agent')
                                        <svg class="w-5 h-5 text-outline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    @else
                                        <svg class="w-5 h-5 text-outline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-headline font-bold text-on-background tracking-tight leading-snug">{{ $portfolio->title }}</h3>
                                    <p class="text-secondary text-sm mt-1.5 leading-relaxed line-clamp-2">{{ $portfolio->description }}</p>
                                    <p class="mt-2 font-label text-[11px] uppercase tracking-wider text-outline">{{ $role }}</p>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach(collect($portfolio->tech_stack ?? [])->take(6) as $tech)
                                            <span class="px-2 py-0.5 bg-surface-container-high text-on-surface-variant font-label text-[11px]">{{ is_string($tech) ? strtoupper($tech) : $tech }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="md:col-span-3 md:text-right">
                        <a href="{{ $href }}" wire:navigate class="inline-flex min-h-11 items-center gap-1 text-primary hover:text-primary-dim transition-colors group/link font-label text-[11px] tracking-widest uppercase">
                            {{ __('View Case Study') }}
                            <svg class="w-3.5 h-3.5 shrink-0 transform group-hover/link:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M7 7h10v10"></path></svg>
                        </a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-10">
        {{ $portfolios->links() }}
    </div>
</div>
