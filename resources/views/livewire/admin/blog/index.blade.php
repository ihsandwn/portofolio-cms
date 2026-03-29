<div>
    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
        <h2 class="text-2xl font-headline font-bold text-on-background">{{ __('Blog Posts') }}</h2>
        <a href="{{ route('admin.blog.create') }}" wire:navigate class="px-4 py-2 bg-primary text-on-primary font-label text-[10px] tracking-widest uppercase hover:bg-primary-dim transition-colors duration-blueprint">
            {{ __('Create New Post') }}
        </a>
    </div>

    @if (session()->has('message'))
        <div class="bg-primary-container/30 border border-outline-variant/20 text-on-primary-container px-4 py-3 mb-4 font-label text-sm">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest border border-outline-variant/20 overflow-hidden">
        <div class="p-4 border-b border-outline-variant/20">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search posts...') }}" class="w-full md:w-1/3 px-4 py-2 border border-outline-variant/20 bg-surface-container-lowest text-on-background focus:ring-2 focus:ring-primary focus:border-primary">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low font-label text-[10px] uppercase tracking-wider text-primary">
                        <th class="p-4">{{ __('Title') }}</th>
                        <th class="p-4">{{ __('Author') }}</th>
                        <th class="p-4">{{ __('Status') }}</th>
                        <th class="p-4">{{ __('Published At') }}</th>
                        <th class="p-4">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse($posts as $post)
                        <tr class="hover:bg-surface-container-low/80 transition-colors duration-blueprint">
                            <td class="p-4 font-medium text-on-background">
                                {{ $post->title }}
                                <div class="text-xs text-secondary">{{ $post->slug }}</div>
                            </td>
                            <td class="p-4 text-secondary">
                                {{ $post->author->name ?? 'Unknown' }}
                            </td>
                            <td class="p-4">
                                @if($post->published_at && $post->published_at <= now())
                                    <span class="px-2 py-1 font-label text-[10px] uppercase tracking-wider border border-outline-variant/20 bg-primary-container/30 text-on-primary-container">{{ __('Published') }}</span>
                                @else
                                    <span class="px-2 py-1 font-label text-[10px] uppercase tracking-wider border border-outline-variant/20 bg-surface-container-high text-secondary">{{ __('Draft') }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-secondary tabular-nums">
                                {{ $post->published_at ? $post->published_at->format('M d, Y') : '-' }}
                            </td>
                            <td class="p-4 space-x-2">
                                <a href="{{ route('admin.blog.edit', $post->id) }}" wire:navigate class="text-primary hover:text-primary-dim font-label text-[10px] uppercase tracking-wider">{{ __('Edit') }}</a>
                                <button type="button" wire:click="delete({{ $post->id }})" wire:confirm="{{ __('Are you sure?') }}" class="text-error hover:opacity-80 font-label text-[10px] uppercase tracking-wider">{{ __('Delete') }}</button>
                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener noreferrer" class="text-secondary hover:text-primary font-label text-[10px] uppercase tracking-wider">{{ __('View') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-secondary">{{ __('No posts found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-outline-variant/20">
            {{ $posts->links() }}
        </div>
    </div>
</div>
