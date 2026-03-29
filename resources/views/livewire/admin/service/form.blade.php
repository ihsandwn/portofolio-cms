@php
    $f = 'w-full bg-surface-container-lowest border border-outline-variant/20 px-4 py-2.5 text-on-background focus:ring-2 focus:ring-primary focus:border-primary';
@endphp
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
        <h1 class="text-2xl font-headline font-bold text-on-background">{{ $is_edit ? __('Edit Service') : __('Create Service') }}</h1>
        <a href="{{ route('admin.services.index') }}" wire:navigate class="text-secondary hover:text-primary font-label text-[10px] uppercase tracking-wider transition-colors duration-blueprint">{{ __('Back to List') }}</a>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="bg-surface-container-lowest p-6 border border-outline-variant/20" x-data="{ lang: 'en' }">
            <div class="flex justify-between items-center mb-4 flex-wrap gap-4">
                <h2 class="text-lg font-headline font-semibold text-on-background">{{ __('Service Details') }}</h2>
                <div class="flex gap-0.5 p-0.5 bg-surface-container-high border border-outline-variant/20">
                    <button type="button" @click="lang = 'en'" :class="lang === 'en' ? 'bg-primary text-on-primary' : 'text-secondary hover:text-on-background'" class="px-3 py-1 font-label text-[10px] uppercase tracking-wider transition-colors duration-blueprint">English</button>
                    <button type="button" @click="lang = 'id'" :class="lang === 'id' ? 'bg-primary text-on-primary' : 'text-secondary hover:text-on-background'" class="px-3 py-1 font-label text-[10px] uppercase tracking-wider transition-colors duration-blueprint">Indonesia</button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Title') }} <span x-show="lang === 'en'" class="text-error">*</span></label>
                    <div x-show="lang === 'en'">
                        <input type="text" wire:model="title.en" class="{{ $f }}" placeholder="e.g. Web Development">
                        @error('title.en') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div x-show="lang === 'id'" style="display: none;">
                        <input type="text" wire:model="title.id" class="{{ $f }}" placeholder="e.g. Pengembangan Web">
                        @error('title.id') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Description') }}</label>
                    <div x-show="lang === 'en'">
                        <textarea wire:model="description.en" rows="4" class="{{ $f }}"></textarea>
                         @error('description.en') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div x-show="lang === 'id'" style="display: none;">
                        <textarea wire:model="description.id" rows="4" class="{{ $f }}"></textarea>
                         @error('description.id') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Category') }}</label>
                    <select wire:model="category" class="{{ $f }}">
                        <option value="web_dev">Web Development</option>
                        <option value="ai_solution">AI Solution</option>
                        <option value="system_arch">System Architecture</option>
                    </select>
                     @error('category') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Icon (SVG Code)') }}</label>
                    <textarea wire:model="icon" rows="3" class="{{ $f }} font-mono text-xs"></textarea>
                    <p class="text-xs text-secondary mt-1">{{ __('Paste standard SVG code (w-6 h-6).') }}</p>
                    @error('icon') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary-dim text-on-primary font-label text-[10px] tracking-widest uppercase transition-colors duration-blueprint">
                {{ $is_edit ? __('Update Service') : __('Create Service') }}
            </button>
        </div>
    </form>
</div>
