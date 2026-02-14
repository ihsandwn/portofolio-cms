<div class="max-w-4xl mx-auto pt-28 md:pt-36 pb-20 px-4 sm:px-6 lg:px-8">
    <article class="rounded-2xl border border-slate-800/80 bg-slate-900/30 p-8 md:p-12">
        <header class="mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ $page->title }}</h1>
            <div class="w-16 h-1 bg-sky-500 rounded-full"></div>
        </header>

        <div class="content-section text-slate-400 space-y-4">
            @safeHtml($page->content ?? '')
        </div>

        <div class="mt-12">
            <a href="mailto:ichsanworkthings@gmail.com" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition">
                {{ __('Contact Me') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z"></path></svg>
            </a>
        </div>
    </article>
</div>
