<div class="max-w-4xl mx-auto pt-32 py-12 px-4 sm:px-6 lg:px-10">
    <nav class="flex mb-8 font-label text-[10px] uppercase tracking-wider text-secondary" aria-label="Breadcrumb">
        <ol class="inline-flex items-center flex-wrap gap-x-2 gap-y-1">
            <li class="inline-flex items-center">
                <a href="/" wire:navigate class="hover:text-primary transition-colors duration-blueprint">Home</a>
            </li>
            <li>
                <span class="text-outline-variant mx-1">/</span>
            </li>
            <li class="inline-flex items-center">
                <a href="{{ route('blog.index') }}" wire:navigate class="hover:text-primary transition-colors duration-blueprint">Blog</a>
            </li>
            <li>
                <span class="text-outline-variant mx-1">/</span>
            </li>
            <li aria-current="page">
                <span class="text-on-background font-headline font-medium truncate max-w-[200px] sm:max-w-md block">{{ $post->title }}</span>
            </li>
        </ol>
    </nav>

    <header class="mb-8">
        <h1 class="font-headline text-4xl font-extrabold text-on-background mb-4">{{ $post->title }}</h1>
    </header>

    <article class="max-w-none space-y-8 text-on-background">
        @foreach($post->blocks as $block)
            @if($block['type'] === 'text')
                <x-blog.text :data="$block['data']" />
            @endif

            @if($block['type'] === 'image' || $block['type'] === 'gallery')
                <x-blog.carousel :images="$block['images'] ?? []" />
            @endif

            @if($block['type'] === 'video')
                <x-blog.video :url="$block['url'] ?? ''" />
            @endif

            @if($block['type'] === 'pdf')
                <x-blog.pdf :file="$block['file'] ?? ''" :title="$block['title'] ?? ''" />
            @endif
        @endforeach
    </article>
</div>
