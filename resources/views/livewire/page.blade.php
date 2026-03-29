<div class="pt-24 pb-16 min-h-screen bg-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-10">
        <h1 class="font-headline text-4xl md:text-5xl font-bold text-on-background mb-8">{{ $page->title }}</h1>

        <div class="prose-blueprint">
            @safeHtml($page->content ?? '')
        </div>
    </div>
</div>
