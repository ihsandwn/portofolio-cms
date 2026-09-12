@php
    $f = 'w-full border border-outline-variant/20 bg-surface-container-lowest px-3 py-2 text-on-background focus:ring-2 focus:ring-primary focus:border-primary';
    $card = 'bg-surface-container-lowest p-6 border border-outline-variant/20';
@endphp
<div>
    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
        <h2 class="text-2xl font-headline font-bold text-on-background">
            {{ $post ? __('Edit Post') : __('Create New Post') }}
        </h2>
        <div class="flex gap-2">
            <button type="button" wire:click="save" class="px-4 py-2 bg-primary text-on-primary font-label text-[10px] tracking-widest uppercase hover:bg-primary-dim transition-colors duration-blueprint">
                <span wire:loading.remove wire:target="save">{{ __('Save Post') }}</span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                    <span class="inline-block h-3 w-3 border-2 border-on-primary border-t-transparent animate-spin rounded-full" aria-hidden="true"></span>
                    {{ __('Saving...') }}
                </span>
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-primary-container/30 border border-outline-variant/20 text-on-primary-container px-4 py-3 mb-4 font-label text-sm">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            <div class="{{ $card }}">
                <div class="mb-4">
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-2">{{ __('Title') }}</label>
                    <input type="text" wire:model.live="title" class="{{ $f }}">
                    @error('title') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-2">{{ __('Slug') }}</label>
                    <input type="text" wire:model="slug" class="{{ $f }}">
                    @error('slug') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="{{ $card }}">
                <h3 class="text-lg font-headline font-bold mb-4 text-on-background">{{ __('Content Blocks') }}</h3>

                <div class="space-y-4 mb-6" wire:sortable="updateBlockOrder">
                    @foreach($content_blocks as $index => $block)
                        <div class="border border-outline-variant/20 p-4 bg-surface-container-low relative" wire:key="block-{{ $index }}">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-label text-[10px] font-bold uppercase tracking-wider text-primary">{{ $block['type'] }}</span>
                                <div class="flex items-center gap-2">
                                    <button type="button" wire:click="moveBlockUp({{ $index }})" class="text-secondary hover:text-primary px-1">{{ __('↑') }}</button>
                                    <button type="button" wire:click="moveBlockDown({{ $index }})" class="text-secondary hover:text-primary px-1">{{ __('↓') }}</button>
                                    <button type="button" wire:click="removeBlock({{ $index }})" class="text-error hover:opacity-80 px-1">{{ __('×') }}</button>
                                </div>
                            </div>

                            @if($block['type'] === 'text')
                                <div>
                                    <textarea wire:model="content_blocks.{{ $index }}.data" rows="5" class="{{ $f }}" placeholder="{{ __('Write content...') }}"></textarea>
                                </div>
                            @endif

                            @if($block['type'] === 'video')
                                <div>
                                    <label class="block text-sm mb-1 text-secondary">{{ __('YouTube/Vimeo Embed URL') }}</label>
                                    <input type="url" wire:model="content_blocks.{{ $index }}.url" class="{{ $f }}" placeholder="https://youtube.com/...">
                                     @error("content_blocks.{$index}.url") <span class="text-error text-xs">{{ $message }}</span> @enderror
                                </div>
                            @endif

                             @if($block['type'] === 'gallery')
                                <div>
                                    <label class="block text-sm mb-1 text-secondary">{{ __('Images') }}</label>
                                    <input type="file" wire:model="content_blocks.{{ $index }}.images" multiple class="block w-full text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:border-0 file:font-label file:text-[10px] file:uppercase file:tracking-wider file:bg-primary-container file:text-on-primary-container hover:file:opacity-90">

                                     @if(isset($block['images']) && is_array($block['images']))
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            @foreach($block['images'] as $imgIndex => $img)
                                                <div class="relative group" wire:key="img-{{ $index }}-{{ $imgIndex }}">
                                                    @if(is_object($img))
                                                        <img src="{{ $img->temporaryUrl() }}" class="h-24 w-24 object-cover border border-outline-variant/20" alt="">
                                                    @elseif(is_string($img))
                                                        <img src="{{ asset('storage/' . $img) }}" class="h-24 w-24 object-cover border border-outline-variant/20" alt="">
                                                    @endif

                                                    <button type="button"
                                                            wire:click="removeImage({{ $index }}, {{ $imgIndex }})"
                                                            class="absolute -top-2 -right-2 bg-error text-on-error p-1 border border-outline-variant/20 opacity-0 group-hover:opacity-100 transition-opacity duration-blueprint">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                     @endif
                                </div>
                            @endif

                             @if($block['type'] === 'pdf')
                                <div>
                                    <label class="block text-sm mb-1 text-secondary">{{ __('PDF Title') }}</label>
                                    <input type="text" wire:model="content_blocks.{{ $index }}.title" class="{{ $f }} mb-2" placeholder="{{ __('E-Book Title') }}">

                                    <label class="block text-sm mb-1 text-secondary">{{ __('PDF File') }}</label>
                                    <input type="file" wire:model="content_blocks.{{ $index }}.file" accept="application/pdf" class="block w-full text-sm text-secondary">

                                    @if(isset($block['file']) && is_string($block['file']))
                                        <div class="mt-1 text-sm text-primary">{{ __('Current file:') }} {{ basename($block['file']) }}</div>
                                    @endif
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>

                <div class="flex flex-wrap gap-2 overflow-x-auto pb-2">
                    <button type="button" wire:click="addBlock('text')" class="px-3 py-1 bg-surface-container-high text-on-background font-label text-[10px] uppercase tracking-wider border border-outline-variant/20 hover:border-primary/40 transition-colors duration-blueprint">+ {{ __('Text') }}</button>
                    <button type="button" wire:click="addBlock('gallery')" class="px-3 py-1 bg-surface-container-high text-on-background font-label text-[10px] uppercase tracking-wider border border-outline-variant/20 hover:border-primary/40 transition-colors duration-blueprint">+ {{ __('Gallery') }}</button>
                    <button type="button" wire:click="addBlock('video')" class="px-3 py-1 bg-surface-container-high text-on-background font-label text-[10px] uppercase tracking-wider border border-outline-variant/20 hover:border-primary/40 transition-colors duration-blueprint">+ {{ __('Video') }}</button>
                    <button type="button" wire:click="addBlock('pdf')" class="px-3 py-1 bg-surface-container-high text-on-background font-label text-[10px] uppercase tracking-wider border border-outline-variant/20 hover:border-primary/40 transition-colors duration-blueprint">+ {{ __('PDF') }}</button>
                </div>
            </div>

             <div class="{{ $card }}">
                <h3 class="text-lg font-headline font-bold mb-4 text-on-background">{{ __('SEO Settings') }}</h3>
                <div class="grid grid-cols-1 gap-4">
                     <div>
                        <label class="block text-sm mb-1 text-secondary">{{ __('Meta Title') }}</label>
                        <input type="text" wire:model="seo_meta.title" class="{{ $f }}">
                    </div>
                     <div>
                        <label class="block text-sm mb-1 text-secondary">{{ __('Meta Description') }}</label>
                        <textarea wire:model="seo_meta.description" class="{{ $f }}"></textarea>
                    </div>
                </div>
            </div>

        </div>

        <div class="space-y-6">
            <div class="{{ $card }}">
                <h3 class="text-lg font-headline font-bold mb-4 text-on-background">{{ __('Publishing') }}</h3>

                <div class="mb-4">
                    <label class="block text-sm mb-1 text-secondary">{{ __('Published At') }}</label>
                    <input type="datetime-local" wire:model="published_at" class="{{ $f }}">
                </div>

                <div class="mb-4">
                     <label class="block text-sm mb-1 text-secondary">{{ __('Category ID (Optional)') }}</label>
                      <input type="number" wire:model="category_id" class="{{ $f }}">
                </div>

                 <div class="mb-4">
                    <label class="block text-sm mb-1 text-secondary">{{ __('Excerpt') }}</label>
                    <textarea wire:model="excerpt" rows="3" class="{{ $f }}"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
