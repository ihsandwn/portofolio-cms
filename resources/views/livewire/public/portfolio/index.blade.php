<div class="max-w-6xl mx-auto pt-28 md:pt-36 pb-20 px-4 sm:px-6 lg:px-8">
    <header class="mb-16">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('Selected Work') }}</h1>
        <p class="text-lg text-slate-400 max-w-2xl">{{ __('Selected Work Description') }}</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($portfolios as $portfolio)
        <a href="{{ $portfolio->type === 'ai_agent' ? route('ai-lab.show', $portfolio->slug) : route('portfolio.show', $portfolio->slug) }}" class="group rounded-xl border border-slate-800/80 bg-slate-900/30 overflow-hidden hover:border-slate-700 transition flex flex-col h-full">
            <div class="h-48 bg-gradient-to-br {{ $portfolio->type === 'ai_agent' ? 'from-slate-800 to-sky-900/30' : 'from-slate-800 to-slate-700' }} flex items-center justify-center relative">
                @if($portfolio->image)
                    <img src="{{ Storage::url($portfolio->image) }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                @else
                    <span class="text-6xl font-bold text-white/10 group-hover:scale-110 transition" aria-hidden="true">{{ substr($portfolio->title, 0, 1) }}</span>
                @endif
                <span class="absolute top-4 right-4 px-2 py-1 rounded bg-slate-900/80 text-xs font-medium text-slate-300">
                    {{ $portfolio->type === 'ai_agent' ? __('AI Solutions') : __('Web Dev') }}
                </span>
            </div>
            <div class="p-6 flex-1 flex flex-col">
                <h2 class="text-xl font-semibold text-white mb-2 group-hover:text-sky-400 transition">{{ $portfolio->title }}</h2>
                <p class="text-slate-400 text-sm line-clamp-3 flex-1">{{ $portfolio->description }}</p>
                <div class="flex flex-wrap gap-2 mt-4 mb-4">
                    @foreach(collect($portfolio->tech_stack)->take(3) as $tech)
                    <span class="text-xs bg-slate-800/80 text-slate-400 px-2 py-1 rounded">{{ $tech }}</span>
                    @endforeach
                </div>
                <span class="inline-flex items-center gap-1.5 text-sm font-medium text-sky-400 group-hover:text-sky-300 transition">
                    {{ __('View Case Study') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </span>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-12">
        {{ $portfolios->links() }}
    </div>
</div>
