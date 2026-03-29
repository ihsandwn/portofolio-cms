<div class="max-w-7xl mx-auto pt-32 pb-12 px-4 sm:px-6 lg:px-10">
    <div class="mb-12 max-w-2xl">
        <h1 class="font-headline text-4xl font-bold text-on-background mb-4">{{ __('Latest Updates') }}</h1>
        <p class="text-lg text-secondary">{{ __('Explore our thoughts on technology, AI, and development.') }}</p>
    </div>

    <div wire:loading.delay class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8" aria-hidden="true">
        @foreach(range(1, 3) as $_)
        <div class="border border-outline-variant/20 bg-surface-container-lowest overflow-hidden animate-pulse">
            <div class="h-48 bg-surface-container-high"></div>
            <div class="p-6 space-y-3">
                <div class="h-3 bg-surface-container w-1/4"></div>
                <div class="h-5 bg-surface-container-high w-4/5"></div>
                <div class="h-3 bg-surface-container w-full"></div>
            </div>
        </div>
        @endforeach
    </div>

    <div wire:loading.remove.delay class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($posts as $post)
            @php
                $type = 'Article';
                $blocks = $post->content_blocks ?? [];
                $types = array_column($blocks, 'type');
                if (in_array('video', $types)) $type = 'Video';
                elseif (in_array('pdf', $types)) $type = 'E-Book';
                elseif (in_array('gallery', $types) || in_array('image', $types)) $type = 'Gallery';
            @endphp

            <article class="border border-outline-variant/20 bg-surface-container-lowest overflow-hidden hover:border-outline-variant/40 transition-colors duration-blueprint flex flex-col h-full">
                <a href="{{ route('blog.show', $post->slug) }}" wire:navigate class="h-48 bg-surface-container relative overflow-hidden group block">
                     <div class="absolute inset-0 bg-primary/5 group-hover:bg-primary/10 transition-colors duration-blueprint"></div>
                     <div class="absolute top-4 right-4 bg-surface-container-lowest/95 border border-outline-variant/20 font-label text-[10px] uppercase tracking-wider text-on-background px-3 py-1">
                         {{ $type }}
                     </div>
                </a>

                <div class="p-6 flex-1 flex flex-col">
                    <div class="font-label text-[10px] uppercase tracking-wider text-primary mb-2">
                        {{ $post->published_at->format('M d, Y') }}
                    </div>
                    <a href="{{ route('blog.show', $post->slug) }}" wire:navigate class="block mt-2 group">
                        <h2 class="text-xl font-headline font-bold text-on-background group-hover:text-primary transition-colors duration-blueprint">{{ $post->title }}</h2>
                        <p class="mt-3 text-secondary line-clamp-3">{{ $post->excerpt }}</p>
                    </a>

                    <div class="mt-auto pt-6 flex items-center justify-between">
                         <div class="flex items-center">
                             <div class="shrink-0">
                                <span class="sr-only">{{ $post->author->name }}</span>
                                <div class="h-8 w-8 bg-primary-container flex items-center justify-center font-label text-xs font-bold text-on-primary-container">{{ substr($post->author->name, 0, 1) }}</div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-on-background">{{ $post->author->name }}</p>
                            </div>
                         </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-secondary">{{ __('No posts found.') }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $posts->links() }}
    </div>
</div>
