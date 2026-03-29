<div class="max-w-4xl mx-auto pt-28 md:pt-36 pb-20 px-4 sm:px-6 lg:px-10">
    <article class="border border-outline-variant/20 bg-surface-container-lowest p-8 md:p-12">
        <header class="mb-10">
            <h1 class="font-headline text-3xl md:text-4xl font-bold text-on-background mb-4">{{ $page->title }}</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-16 bg-primary"></span>
                <span class="font-label text-[10px] uppercase tracking-[0.2em] text-secondary">{{ __('Profile') }}</span>
            </div>
        </header>

        <div class="content-section text-secondary space-y-4">
            @safeHtml($page->content ?? '')
        </div>

        <div class="mt-12">
            <a href="mailto:ichsanworkthings@gmail.com" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-on-primary font-label text-[10px] tracking-widest uppercase hover:bg-primary-dim transition-colors duration-blueprint">
                {{ __('Contact Me') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z"></path></svg>
            </a>
        </div>
    </article>
</div>
