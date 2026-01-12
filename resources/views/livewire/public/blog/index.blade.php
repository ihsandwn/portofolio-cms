<div class="max-w-7xl mx-auto pt-32 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Latest Updates</h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Explore our thoughts on technology, AI, and development.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($posts as $post)
            @php
                $type = 'Article';
                $blocks = $post->content_blocks ?? [];
                $types = array_column($blocks, 'type');
                if (in_array('video', $types)) $type = 'Video';
                elseif (in_array('pdf', $types)) $type = 'E-Book';
                elseif (in_array('gallery', $types) || in_array('image', $types)) $type = 'Gallery';
            @endphp

            <article class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300 flex flex-col h-full border border-gray-100 dark:border-gray-700">
                <!-- Image Placeholder -->
                <a href="{{ route('blog.show', $post->slug) }}" class="h-48 bg-gradient-to-r from-blue-500 to-indigo-600 relative overflow-hidden group block">
                     <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition"></div>
                     
                     <!-- Content Type Badge -->
                     <div class="absolute top-4 right-4 bg-black/60 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-white/20">
                         {{ $type }}
                     </div>
                </a>
                
                <div class="p-6 flex-1 flex flex-col">
                    <div class="text-sm text-blue-600 dark:text-blue-400 mb-2 font-semibold">
                        {{ $post->published_at->format('M d, Y') }}
                    </div>
                    <a href="{{ route('blog.show', $post->slug) }}" class="block mt-2">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white hover:text-blue-600 transition">{{ $post->title }}</h2>
                        <p class="mt-3 text-gray-500 dark:text-gray-400 line-clamp-3">{{ $post->excerpt }}</p>
                    </a>
                    
                    <div class="mt-auto pt-6 flex items-center justify-between">
                         <div class="flex items-center">
                             <div class="flex-shrink-0">
                                <span class="sr-only">{{ $post->author->name }}</span>
                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-700">{{ substr($post->author->name, 0, 1) }}</div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $post->author->name }}</p>
                            </div>
                         </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-gray-500">No posts found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $posts->links() }}
    </div>
</div>
