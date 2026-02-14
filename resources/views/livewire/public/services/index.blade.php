<div class="max-w-6xl mx-auto pt-28 md:pt-36 pb-20 px-4 sm:px-6 lg:px-8">
    <header class="mb-16">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('Expertise & Services') }}</h1>
        <p class="text-lg text-slate-400 max-w-2xl">{{ __('Expertise Description') }}</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($services as $service)
        <article class="p-8 rounded-xl border border-slate-800/80 bg-slate-900/30 hover:border-slate-700 transition">
            <div class="w-12 h-12 rounded-lg bg-sky-500/10 flex items-center justify-center text-sky-400 mb-6">
                {!! $service->icon ?? '' !!}
            </div>
            <h2 class="text-xl font-semibold text-white mb-3">{{ $service->title }}</h2>
            <p class="text-slate-400 leading-relaxed mb-6">{{ $service->description }}</p>
            <span class="text-xs font-medium text-sky-400/80 uppercase tracking-wider">{{ str_replace('_', ' ', $service->category) }}</span>
        </article>
        @endforeach
    </div>
</div>
