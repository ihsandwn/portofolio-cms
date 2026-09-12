<div class="max-w-7xl mx-auto pt-28 md:pt-32 pb-24 px-6">
    <header class="mb-12 md:mb-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="font-headline text-3xl md:text-4xl font-bold text-on-background mb-4">{{ __('Selected Work') }}</h1>
                <p class="text-sm md:text-base text-secondary max-w-xl leading-relaxed">{{ __('Selected Work Description') }}</p>
            </div>
        </div>
    </header>

    <div class="w-full overflow-x-auto border border-outline-variant/20 bg-surface-container-lowest">
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
                                    <div class="font-headline font-bold text-on-background tracking-tight">{{ $portfolio->title }}</div>
                                    <p class="text-secondary text-xs mt-1 line-clamp-2 max-w-md">{{ $portfolio->description }}</p>
                                    <div class="lg:hidden mt-2 flex flex-wrap gap-2">
                                        @foreach(collect($portfolio->tech_stack ?? [])->take(4) as $tech)
                                            <span class="font-label text-[9px] text-primary">{{ is_string($tech) ? strtoupper($tech) : $tech }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-6 md:py-8 px-4 hidden lg:table-cell align-top">
                            <div class="flex flex-wrap gap-2">
                                @foreach(collect($portfolio->tech_stack ?? [])->take(8) as $tech)
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

    <div class="mt-10">
        {{ $portfolios->links() }}
    </div>
</div>
