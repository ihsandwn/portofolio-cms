<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Blog Posts</h2>
        <a href="{{ route('admin.blog.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            Create New Post
        </a>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search posts..." class="w-full md:w-1/3 px-4 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-200 uppercase text-xs">
                        <th class="p-4">Title</th>
                        <th class="p-4">Author</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Published At</th>
                        <th class="p-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($posts as $post)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                            <td class="p-4 font-medium text-gray-900 dark:text-white">
                                {{ $post->title }}
                                <div class="text-xs text-gray-500">{{ $post->slug }}</div>
                            </td>
                            <td class="p-4 text-gray-600 dark:text-gray-300">
                                {{ $post->author->name ?? 'Unknown' }}
                            </td>
                            <td class="p-4">
                                @if($post->published_at && $post->published_at <= now())
                                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Published</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Draft</span>
                                @endif
                            </td>
                            <td class="p-4 text-gray-600 dark:text-gray-300">
                                {{ $post->published_at ? $post->published_at->format('M d, Y') : '-' }}
                            </td>
                            <td class="p-4 space-x-2">
                                <a href="{{ route('admin.blog.edit', $post->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <button wire:click="delete({{ $post->id }})" wire:confirm="Are you sure?" class="text-red-600 hover:text-red-800">Delete</button>
                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="text-gray-500 hover:text-gray-700">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">No posts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>
