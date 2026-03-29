@php
    $field = 'w-full bg-surface-container-lowest border border-outline-variant/20 px-4 py-2 text-on-background placeholder:text-on-surface-variant focus:ring-2 focus:ring-primary focus:border-primary';
@endphp
<div>
    <form wire:submit="save" class="max-w-4xl mx-auto space-y-6">

        <div class="bg-surface-container-lowest p-6 border border-outline-variant/20" x-data="{ lang: 'en' }">
            <div class="flex justify-between items-center mb-4 flex-wrap gap-4">
                <h2 class="text-lg font-headline font-semibold text-on-background">{{ __('Basic Information') }}</h2>
                <div class="flex gap-0.5 p-0.5 bg-surface-container-high border border-outline-variant/20">
                    <button type="button" @click="lang = 'en'" :class="lang === 'en' ? 'bg-primary text-on-primary' : 'text-secondary hover:text-on-background'" class="px-3 py-1 font-label text-[10px] uppercase tracking-wider transition-colors duration-blueprint">English</button>
                    <button type="button" @click="lang = 'id'" :class="lang === 'id' ? 'bg-primary text-on-primary' : 'text-secondary hover:text-on-background'" class="px-3 py-1 font-label text-[10px] uppercase tracking-wider transition-colors duration-blueprint">Indonesia</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Project Title') }}</label>
                    <div x-show="lang === 'en'">
                        <input wire:model.live="title.en" type="text" placeholder="Title in English" class="{{ $field }}">
                        @error('title.en') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div x-show="lang === 'id'" style="display: none;">
                        <input wire:model.live="title.id" type="text" placeholder="Judul dalam Bahasa Indonesia" class="{{ $field }}">
                        @error('title.id') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Slug') }}</label>
                    <input wire:model="slug" type="text" class="{{ $field }}">
                    @error('slug') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Project Type') }}</label>
                    <select wire:model="type" class="{{ $field }}">
                        <option value="web">Web Development</option>
                        <option value="ai_agent">AI Agent / Automation</option>
                        <option value="consulting">Consulting / Architecture</option>
                    </select>
                </div>

                <div>
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Client Name') }}</label>
                    <input wire:model="client" type="text" class="{{ $field }}">
                </div>
            </div>

            <div class="mt-4">
                <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Short Description') }}</label>
                <div x-show="lang === 'en'">
                    <textarea wire:model="description.en" rows="2" placeholder="Description in English" class="{{ $field }}"></textarea>
                    @error('description.en') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>
                <div x-show="lang === 'id'" style="display: none;">
                    <textarea wire:model="description.id" rows="2" placeholder="Deskripsi dalam Bahasa Indonesia" class="{{ $field }}"></textarea>
                    @error('description.id') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-6 border border-outline-variant/20">
            <h2 class="text-lg font-headline font-semibold text-on-background mb-4">{{ __('Technical Details') }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Project URL') }}</label>
                    <input wire:model="url" type="url" placeholder="https://" class="{{ $field }}">
                    @error('url') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Repository URL') }}</label>
                    <input wire:model="repo_url" type="url" placeholder="https://github.com/..." class="{{ $field }}">
                </div>
            </div>

            <div class="mt-4">
                <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">{{ __('Tech Stack (comma-separated)') }}</label>
                <input wire:model="tech_stack_input" type="text" placeholder="Laravel, Vue.js, Tailwind, OpenAI" class="{{ $field }}">
                <p class="text-xs text-secondary mt-1">{{ __('Separate technologies with commas.') }}</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-6 border border-outline-variant/20">
            <h2 class="text-lg font-headline font-semibold text-on-background mb-4">{{ __('Case Study (Markdown)') }}</h2>
            <textarea wire:model="case_study" rows="10" placeholder="## Problem..." class="{{ $field }} font-mono text-sm"></textarea>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 bg-surface-container-lowest p-6 border border-outline-variant/20">
            <label class="flex items-center gap-3 cursor-pointer">
                <input wire:model="is_featured" type="checkbox" class="h-5 w-5 border-outline-variant text-primary focus:ring-primary bg-surface-container-lowest">
                <span class="text-on-background font-medium">{{ __('Feature on Homepage') }}</span>
            </label>

            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.portfolios.index') }}" wire:navigate class="px-6 py-2 border border-outline text-on-background font-label text-[10px] uppercase tracking-widest hover:bg-surface-container-low transition-colors duration-blueprint text-center">{{ __('Cancel') }}</a>
                <button type="submit" class="px-6 py-2 bg-primary hover:bg-primary-dim text-on-primary font-label text-[10px] uppercase tracking-widest transition-colors duration-blueprint">
                    {{ $is_edit ? __('Update Portfolio') : __('Create Portfolio') }}
                </button>
            </div>
        </div>

    </form>
</div>
