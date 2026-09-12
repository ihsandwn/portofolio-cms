<div class="space-y-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <h1 class="text-2xl font-headline font-bold text-on-background">{{ __('Services') }}</h1>
        <a href="{{ route('admin.services.create') }}" wire:navigate class="px-4 py-2 bg-primary hover:bg-primary-dim text-on-primary font-label text-[10px] tracking-widest uppercase transition-colors duration-blueprint">{{ __('Add Service') }}</a>
    </div>

    @if (session()->has('success'))
        <div class="bg-primary-container/30 text-on-primary-container p-4 border border-outline-variant/20 font-label text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest border border-outline-variant/20 overflow-hidden">
        <table class="w-full text-left text-sm text-secondary">
            <thead class="bg-surface-container-low text-on-background font-label text-[10px] uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">{{ __('Title (EN)') }}</th>
                    <th class="px-6 py-4">{{ __('Title (ID)') }}</th>
                    <th class="px-6 py-4">{{ __('Category') }}</th>
                    <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @foreach($services as $service)
                    <tr class="hover:bg-surface-container-low/80 transition-colors duration-blueprint">
                        <td class="px-6 py-4 font-medium text-on-background">{{ $service->title['en'] ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $service->title['id'] ?? '-' }}</td>
                        <td class="px-6 py-4 capitalize">{{ str_replace('_', ' ', $service->category) }}</td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('admin.services.edit', $service) }}" wire:navigate class="text-primary hover:text-primary-dim font-label text-[10px] uppercase tracking-wider transition-colors duration-blueprint">{{ __('Edit') }}</a>
                            <button type="button" wire:click="delete({{ $service->id }})"
                                    wire:confirm="{{ __('Are you sure you want to delete this service?') }}"
                                    class="text-error hover:opacity-80 font-label text-[10px] uppercase tracking-wider transition-opacity duration-blueprint">{{ __('Delete') }}</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
