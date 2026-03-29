<div class="space-y-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <h1 class="text-2xl font-headline font-bold text-on-background">{{ __('Pages') }}</h1>
        <a href="{{ route('admin.pages.create') }}" wire:navigate class="px-4 py-2 bg-primary hover:bg-primary-dim text-on-primary font-label text-[10px] tracking-widest uppercase transition-colors duration-blueprint">{{ __('Create Page') }}</a>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant/20 overflow-hidden">
        <table class="w-full text-left text-sm text-secondary">
            <thead class="bg-surface-container-low text-on-background font-label text-[10px] uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">{{ __('Title') }}</th>
                    <th class="px-6 py-4">{{ __('Slug') }}</th>
                    <th class="px-6 py-4">{{ __('Status') }}</th>
                    <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @foreach($pages as $page)
                    <tr class="hover:bg-surface-container-low/80 transition-colors duration-blueprint">
                        <td class="px-6 py-4 font-medium text-on-background">{{ $page->title['en'] }}</td>
                        <td class="px-6 py-4 text-outline">{{ $page->slug }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 font-label text-[10px] uppercase tracking-wider border {{ $page->is_active ? 'bg-primary-container/30 text-on-primary-container border-outline-variant/20' : 'bg-error-container/20 text-on-error-container border-outline-variant/20' }}">
                                {{ $page->is_active ? __('Active') : __('Draft') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('admin.pages.edit', $page) }}" wire:navigate class="text-primary hover:text-primary-dim font-label text-[10px] uppercase tracking-wider">{{ __('Edit') }}</a>
                            <button type="button" wire:click="delete({{ $page->id }})" wire:confirm="{{ __('Are you sure you want to delete this page?') }}" class="text-error hover:opacity-80 font-label text-[10px] uppercase tracking-wider">{{ __('Delete') }}</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
