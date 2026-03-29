@php
    $f = 'w-full bg-surface-container-lowest border border-outline-variant/20 px-3 py-2 text-on-background focus:ring-2 focus:ring-primary focus:border-primary';
@endphp
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-headline font-bold text-on-background">{{ __('Menu Builder') }}: {{ str_replace('_', ' ', $menu->name) }}</h1>
            <a href="{{ route('admin.menus.index') }}" wire:navigate class="text-secondary hover:text-primary font-label text-[10px] uppercase tracking-wider transition-colors duration-blueprint">{{ __('Back to Menus') }}</a>
        </div>
        <button type="button" wire:click="create" class="px-4 py-2 bg-primary hover:bg-primary-dim text-on-primary font-label text-[10px] tracking-widest uppercase transition-colors duration-blueprint">{{ __('Add Item') }}</button>
    </div>

    @if (session()->has('success'))
        <div class="bg-primary-container/30 text-on-primary-container p-4 border border-outline-variant/20 mb-6 font-label text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest border border-outline-variant/20 overflow-hidden">
        <ul class="divide-y divide-outline-variant/20">
            @foreach($items as $item)
                <li class="p-4 hover:bg-surface-container-low/80 transition-colors duration-blueprint flex items-center justify-between group">
                    <div>
                        <div class="font-medium text-on-background flex items-center flex-wrap gap-2">
                            {{ $item->title['en'] }}
                            <span class="font-label text-[10px] uppercase tracking-wider text-secondary border border-outline-variant/30 px-1.5 py-0.5">{{ __('Order') }}: {{ $item->order }}</span>
                        </div>
                        <div class="text-sm text-secondary">{{ $item->url }}</div>
                         @if(isset($item->title['id']) && $item->title['id'])
                            <div class="text-xs text-outline mt-1">ID: {{ $item->title['id'] }}</div>
                        @endif
                    </div>
                    <div class="flex gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-blueprint">
                        <button type="button" wire:click="edit({{ $item->id }})" class="text-primary hover:text-primary-dim font-label text-[10px] uppercase tracking-wider">{{ __('Edit') }}</button>
                        <button type="button" wire:click="delete({{ $item->id }})" class="text-error hover:opacity-80 font-label text-[10px] uppercase tracking-wider" wire:confirm="{{ __('Delete this item?') }}">{{ __('Delete') }}</button>
                    </div>
                </li>
            @endforeach

            @if($items->isEmpty())
                <li class="p-8 text-center text-secondary">{{ __('No menu items found.') }}</li>
            @endif
        </ul>
    </div>

    <div x-show="$wire.showForm" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-inverse-surface/50 backdrop-blur-sm" wire:click="$set('showForm', false)"></div>
            </div>

            <div class="inline-block align-bottom bg-surface-container-lowest text-left overflow-hidden border border-outline-variant/20 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="save">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-headline font-medium text-on-background mb-4" id="modal-title">
                            {{ $editingItem ? __('Edit Item') : __('Add Item') }}
                        </h3>

                        <div class="space-y-4" x-data="{ lang: 'en' }">
                            <div class="flex gap-0.5 p-0.5 bg-surface-container-high border border-outline-variant/20 w-fit">
                                <button type="button" @click="lang = 'en'" :class="lang === 'en' ? 'bg-primary text-on-primary' : 'text-secondary hover:text-on-background'" class="px-3 py-1 font-label text-[10px] uppercase tracking-wider transition-colors duration-blueprint">EN</button>
                                <button type="button" @click="lang = 'id'" :class="lang === 'id' ? 'bg-primary text-on-primary' : 'text-secondary hover:text-on-background'" class="px-3 py-1 font-label text-[10px] uppercase tracking-wider transition-colors duration-blueprint">ID</button>
                            </div>

                            <div>
                                <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Title') }}</label>
                                <div x-show="lang === 'en'">
                                    <input type="text" wire:model="title.en" class="{{ $f }}" placeholder="English Title">
                                    @error('title.en') <span class="text-error text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div x-show="lang === 'id'">
                                    <input type="text" wire:model="title.id" class="{{ $f }}" placeholder="Indonesia Title">
                                </div>
                            </div>

                            <div>
                                <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('URL / Hash') }}</label>
                                <input type="text" wire:model="url" class="{{ $f }}" placeholder="#about or /path">
                                @error('url') <span class="text-error text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Order') }}</label>
                                <input type="number" wire:model="order" class="w-24 {{ $f }}">
                            </div>
                        </div>
                    </div>
                    <div class="bg-surface-container-low px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3 border-t border-outline-variant/20">
                        <button type="submit" class="w-full inline-flex justify-center border border-transparent px-4 py-2 bg-primary text-on-primary font-label text-[10px] uppercase tracking-widest hover:bg-primary-dim sm:w-auto transition-colors duration-blueprint">
                            {{ __('Save') }}
                        </button>
                        <button type="button" wire:click="$set('showForm', false)" class="mt-3 w-full inline-flex justify-center border border-outline px-4 py-2 bg-surface-container-lowest text-on-background font-label text-[10px] uppercase tracking-wider hover:bg-surface-container sm:mt-0 sm:w-auto transition-colors duration-blueprint">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
