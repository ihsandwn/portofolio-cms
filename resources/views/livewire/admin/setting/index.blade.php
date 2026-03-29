@php
    $fin = 'w-full bg-surface-container-lowest border border-outline-variant/20 px-3 py-2 text-on-background focus:ring-2 focus:ring-primary focus:border-primary';
@endphp
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-headline font-bold text-on-background">{{ __('Site Settings') }}</h1>
    </div>

    @foreach($settings_group as $group => $settings)
        <div class="bg-surface-container-lowest p-6 border border-outline-variant/20">
            <h2 class="text-lg font-headline font-semibold text-on-background capitalize mb-4 border-b border-outline-variant/20 pb-2">{{ $group }} {{ __('Settings') }}</h2>

            <div class="space-y-4">
                @foreach($settings as $setting)
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start" x-data="{ lang: 'en' }">
                        <div class="md:col-span-1">
                            <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1 capitalize">
                                {{ str_replace('_', ' ', $setting->key) }}
                            </label>
                            <p class="text-xs text-outline">Key: {{ $setting->key }}</p>
                        </div>

                        <div class="md:col-span-3">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex gap-2 items-center">
                                    <button type="button" @click="lang = 'en'" :class="lang === 'en' ? 'text-primary font-bold' : 'text-secondary hover:text-on-background'" class="font-label text-[10px] uppercase tracking-wider transition-colors duration-blueprint">EN</button>
                                    <span class="text-outline-variant">|</span>
                                    <button type="button" @click="lang = 'id'" :class="lang === 'id' ? 'text-primary font-bold' : 'text-secondary hover:text-on-background'" class="font-label text-[10px] uppercase tracking-wider transition-colors duration-blueprint">ID</button>
                                </div>
                                <span class="text-xs text-primary font-label uppercase" x-data="{ show: false }" x-init="@this.on('saved', () => { show = true; setTimeout(() => show = false, 2000) })" x-show="show" x-transition>{{ __('Saved!') }}</span>
                            </div>

                            <div x-show="lang === 'en'">
                                @if($setting->type === 'text')
                                    <input type="text"
                                           wire:change="update({{ $setting->id }}, 'value.en', $event.target.value)"
                                           value="{{ $setting->value['en'] ?? '' }}"
                                           class="{{ $fin }}">
                                @elseif($setting->type === 'textarea')
                                    <textarea wire:change="update({{ $setting->id }}, 'value.en', $event.target.value)"
                                              class="{{ $fin }}" rows="3">{{ $setting->value['en'] ?? '' }}</textarea>
                                @endif
                            </div>

                             <div x-show="lang === 'id'">
                                @if($setting->type === 'text')
                                    <input type="text"
                                           wire:change="update({{ $setting->id }}, 'value.id', $event.target.value)"
                                           value="{{ $setting->value['id'] ?? '' }}"
                                           class="{{ $fin }}">
                                @elseif($setting->type === 'textarea')
                                    <textarea wire:change="update({{ $setting->id }}, 'value.id', $event.target.value)"
                                              class="{{ $fin }}" rows="3">{{ $setting->value['id'] ?? '' }}</textarea>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
