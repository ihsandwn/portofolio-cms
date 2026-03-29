<div>
    <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 mb-6">
        <div class="relative w-full sm:w-64">
             <input wire:model.live="search" type="text" placeholder="{{ __('Search...') }}" class="w-full bg-surface-container-lowest text-on-background border border-outline-variant/20 px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary focus:outline-none transition-shadow duration-blueprint">
        </div>
        <a href="{{ route('admin.portfolios.create') }}" wire:navigate class="inline-flex justify-center bg-primary hover:bg-primary-dim text-on-primary px-4 py-2 font-label text-[10px] tracking-widest uppercase transition-colors duration-blueprint shrink-0">
            + {{ __('New Project') }}
        </a>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant/20 overflow-hidden">
        <table class="w-full text-left text-sm text-secondary">
            <thead class="bg-surface-container-low text-on-background font-label text-[10px] uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">{{ __('Title') }}</th>
                    <th class="px-6 py-4">{{ __('Client') }}</th>
                    <th class="px-6 py-4">{{ __('Type') }}</th>
                    <th class="px-6 py-4">{{ __('Featured') }}</th>
                    <th class="px-6 py-4">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($portfolios as $portfolio)
                <tr class="hover:bg-surface-container-low/80 transition-colors duration-blueprint">
                    <td class="px-6 py-4 font-medium text-on-background">
                        {{ $portfolio->title }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $portfolio->client ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 font-label text-[10px] uppercase tracking-wider border border-outline-variant/30
                            {{ $portfolio->type === 'ai_agent' ? 'bg-tertiary-container/40 text-on-tertiary-container' : 'bg-primary-container/40 text-on-primary-container' }}">
                            {{ ucfirst(str_replace('_', ' ', $portfolio->type)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($portfolio->is_featured)
                            <span class="text-primary font-medium">{{ __('Yes') }}</span>
                        @else
                            <span class="text-outline">{{ __('No') }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex flex-wrap gap-3">
                        <a href="{{ route('admin.portfolios.edit', $portfolio) }}" wire:navigate class="text-primary hover:text-primary-dim font-label text-[10px] uppercase tracking-wider">{{ __('Edit') }}</a>
                        <button type="button" wire:confirm="{{ __('Are you sure?') }}" wire:click="delete({{ $portfolio->id }})" class="text-error hover:opacity-80 font-label text-[10px] uppercase tracking-wider">{{ __('Delete') }}</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-secondary">
                        {{ __('No portfolios found.') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $portfolios->links() }}
    </div>
</div>
