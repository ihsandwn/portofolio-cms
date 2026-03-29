@php
    $f = 'w-full bg-surface-container-lowest border border-outline-variant/20 px-3 py-2 text-on-background focus:ring-2 focus:ring-primary focus:border-primary';
@endphp
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
        <h1 class="text-2xl font-headline font-bold text-on-background">{{ $page->exists ? __('Edit Page') : __('Create Page') }}</h1>
        <a href="{{ route('admin.pages.index') }}" wire:navigate class="text-secondary hover:text-primary font-label text-[10px] uppercase tracking-wider transition-colors duration-blueprint">{{ __('Back to Pages') }}</a>
    </div>

    <form wire:submit="save" class="space-y-8" x-data="{ lang: 'en' }">
        <div class="flex gap-0.5 p-0.5 bg-surface-container-high border border-outline-variant/20 w-fit">
            <button type="button" @click="lang = 'en'" :class="lang === 'en' ? 'bg-primary text-on-primary' : 'text-secondary hover:text-on-background'" class="px-4 py-2 font-label text-[10px] uppercase tracking-wider transition-colors duration-blueprint">English</button>
            <button type="button" @click="lang = 'id'" :class="lang === 'id' ? 'bg-primary text-on-primary' : 'text-secondary hover:text-on-background'" class="px-4 py-2 font-label text-[10px] uppercase tracking-wider transition-colors duration-blueprint">Indonesia</button>
        </div>

        <div class="bg-surface-container-lowest p-6 border border-outline-variant/20 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Slug') }}</label>
                    <input type="text" wire:model="slug" class="{{ $f }}">
                    @error('slug') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="h-5 w-5 border-outline-variant text-primary focus:ring-primary bg-surface-container-lowest">
                        <span class="text-on-background">{{ __('Active') }}</span>
                    </label>
                </div>
            </div>

            <div x-show="lang === 'en'" class="space-y-6">
                <div>
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Title (EN)') }}</label>
                    <input type="text" wire:model.live="title.en" class="{{ $f }}">
                    @error('title.en') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Content (EN)') }}</label>
                    <textarea wire:model="content.en" rows="10" class="{{ $f }} font-mono text-sm"></textarea>
                    <p class="text-xs text-secondary mt-1">{{ __('HTML is supported.') }}</p>
                    @error('content.en') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div x-show="lang === 'id'" class="space-y-6" style="display: none;">
                <div>
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Title (ID)') }}</label>
                    <input type="text" wire:model="title.id" class="{{ $f }}">
                </div>
                <div>
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Content (ID)') }}</label>
                    <textarea wire:model="content.id" rows="10" class="{{ $f }} font-mono text-sm"></textarea>
                    <p class="text-xs text-secondary mt-1">{{ __('HTML is supported.') }}</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-primary hover:bg-primary-dim text-on-primary font-label text-[10px] tracking-widest uppercase transition-colors duration-blueprint">{{ __('Save Page') }}</button>
        </div>
    </form>
</div>
