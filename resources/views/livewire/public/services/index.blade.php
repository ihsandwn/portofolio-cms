<div class="max-w-6xl mx-auto pt-28 md:pt-36 pb-20 px-4 sm:px-6 lg:px-10">
    <header class="mb-16">
        <h1 class="font-headline text-3xl md:text-4xl font-bold text-on-background mb-4">{{ __('Expertise & Services') }}</h1>
        <p class="text-lg text-secondary max-w-2xl">{{ __('Expertise Description') }}</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($services as $service)
        <article class="p-8 border border-outline-variant/20 bg-surface-container-lowest hover:border-outline-variant/40 transition-colors duration-blueprint">
            <div class="w-12 h-12 bg-primary-container flex items-center justify-center text-on-primary-container mb-6 [&_svg]:w-6 [&_svg]:h-6">
                {!! $service->icon ?? '' !!}
            </div>
            <h2 class="text-xl font-headline font-semibold text-on-background mb-3">{{ $service->title }}</h2>
            <p class="text-secondary leading-relaxed mb-6">{{ $service->description }}</p>
            <span class="font-label text-[10px] font-semibold text-primary uppercase tracking-[0.15em]">{{ str_replace('_', ' ', $service->category) }}</span>
        </article>
        @endforeach
    </div>
</div>
