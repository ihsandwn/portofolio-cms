<div class="pt-24 pb-16 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-8">{{ $page->title }}</h1>
        
        <div class="prose prose-lg prose-invert max-w-none">
            @safeHtml($page->content ?? '')
        </div>
    </div>
</div>
