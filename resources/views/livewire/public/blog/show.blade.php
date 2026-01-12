<div class="max-w-4xl mx-auto pt-32 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8 text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li class="inline-flex items-center">
                <a href="/" class="hover:text-blue-600 dark:hover:text-white transition-colors">Home</a>
            </li>
            <li>
                <span class="mx-2">/</span>
            </li>
            <li class="inline-flex items-center">
                <a href="{{ route('blog.index') }}" class="hover:text-blue-600 dark:hover:text-white transition-colors">Blog</a>
            </li>
            <li>
                <span class="mx-2">/</span>
            </li>
            <li aria-current="page">
                <span class="text-gray-900 dark:text-white font-medium truncate max-w-[200px] sm:max-w-md block">{{ $post->title }}</span>
            </li>
        </ol>
    </nav>

    <header class="mb-8 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">{{ $post->title }}</h1>
    </header>

    <article class="prose dark:prose-invert max-w-none space-y-8">
        @foreach($post->content_blocks as $block)
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
