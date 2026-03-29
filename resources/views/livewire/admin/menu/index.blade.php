<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-headline font-bold text-on-background">{{ __('Menus') }}</h1>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant/20 overflow-hidden">
        <table class="w-full text-left text-sm text-secondary">
            <thead class="bg-surface-container-low text-on-background font-label text-[10px] uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">{{ __('Name') }}</th>
                    <th class="px-6 py-4">{{ __('Status') }}</th>
                    <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @foreach($menus as $menu)
                    <tr class="hover:bg-surface-container-low/80 transition-colors duration-blueprint">
                        <td class="px-6 py-4 font-medium text-on-background capitalize">{{ str_replace('_', ' ', $menu->name) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 font-label text-[10px] uppercase tracking-wider border {{ $menu->is_active ? 'bg-primary-container/30 text-on-primary-container border-outline-variant/20' : 'bg-error-container/20 text-on-error-container border-outline-variant/20' }}">
                                {{ $menu->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('admin.menus.builder', $menu) }}" wire:navigate class="text-primary hover:text-primary-dim font-label text-[10px] uppercase tracking-wider">{{ __('Builder') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
