<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-slate-100">Site Settings</h1>
    </div>

    @foreach($settings_group as $group => $settings)
        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
            <h2 class="text-lg font-semibold text-slate-100 capitalize mb-4 border-b border-slate-700 pb-2">{{ $group }} Settings</h2>
            
            <div class="space-y-4">
                @foreach($settings as $setting)
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start" x-data="{ lang: 'en' }">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-slate-400 mb-1 capitalize">
                                {{ str_replace('_', ' ', $setting->key) }}
                            </label>
                            <p class="text-xs text-slate-500">Key: {{ $setting->key }}</p>
                        </div>
                        
                        <div class="md:col-span-3">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex space-x-1">
                                    <button @click="lang = 'en'" :class="lang === 'en' ? 'text-indigo-400 font-bold' : 'text-slate-500 hover:text-slate-300'" class="text-xs uppercase">EN</button>
                                    <span class="text-slate-600">|</span>
                                    <button @click="lang = 'id'" :class="lang === 'id' ? 'text-indigo-400 font-bold' : 'text-slate-500 hover:text-slate-300'" class="text-xs uppercase">ID</button>
                                </div>
                                <span class="text-xs text-green-500" x-data="{ show: false }" x-init="@this.on('saved', () => { show = true; setTimeout(() => show = false, 2000) })" x-show="show" x-transition>Saved!</span>
                            </div>

                            <!-- English Input -->
                            <div x-show="lang === 'en'">
                                @if($setting->type === 'text')
                                    <input type="text" 
                                           wire:change="update({{ $setting->id }}, 'value.en', $event.target.value)"
                                           value="{{ $setting->value['en'] ?? '' }}"
                                           class="w-full bg-slate-900 border border-slate-700 rounded-md px-3 py-2 text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                @elseif($setting->type === 'textarea')
                                    <textarea wire:change="update({{ $setting->id }}, 'value.en', $event.target.value)"
                                              class="w-full bg-slate-900 border border-slate-700 rounded-md px-3 py-2 text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500" rows="3">{{ $setting->value['en'] ?? '' }}</textarea>
                                @endif
                            </div>

                            <!-- Indonesian Input -->
                             <div x-show="lang === 'id'">
                                @if($setting->type === 'text')
                                    <input type="text" 
                                           wire:change="update({{ $setting->id }}, 'value.id', $event.target.value)"
                                           value="{{ $setting->value['id'] ?? '' }}"
                                           class="w-full bg-slate-900 border border-slate-700 rounded-md px-3 py-2 text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                @elseif($setting->type === 'textarea')
                                    <textarea wire:change="update({{ $setting->id }}, 'value.id', $event.target.value)"
                                              class="w-full bg-slate-900 border border-slate-700 rounded-md px-3 py-2 text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500" rows="3">{{ $setting->value['id'] ?? '' }}</textarea>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
