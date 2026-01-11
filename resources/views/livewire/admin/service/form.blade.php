<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-100">{{ $is_edit ? 'Edit Service' : 'Create Service' }}</h1>
        <a href="{{ route('admin.services.index') }}" class="text-slate-400 hover:text-white transition">Back to List</a>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700" x-data="{ lang: 'en' }">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-slate-100">Service Details</h2>
                
                <!-- Language Tabs -->
                <div class="flex space-x-1 bg-slate-900 p-1 rounded-lg">
                    <button type="button" @click="lang = 'en'" :class="lang === 'en' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white'" class="px-3 py-1 text-xs font-medium rounded transition">English</button>
                    <button type="button" @click="lang = 'id'" :class="lang === 'id' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white'" class="px-3 py-1 text-xs font-medium rounded transition">Indonesia</button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Title <span x-show="lang === 'en'" class="text-red-500">*</span></label>
                    <div x-show="lang === 'en'">
                        <input type="text" wire:model="title.en" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="e.g. Web Development">
                        @error('title.en') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div x-show="lang === 'id'" style="display: none;">
                        <input type="text" wire:model="title.id" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="e.g. Pengembangan Web">
                        @error('title.id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Description</label>
                    <div x-show="lang === 'en'">
                        <textarea wire:model="description.en" rows="4" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"></textarea>
                         @error('description.en') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div x-show="lang === 'id'" style="display: none;">
                        <textarea wire:model="description.id" rows="4" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"></textarea>
                         @error('description.id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Category</label>
                    <select wire:model="category" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        <option value="web_dev">Web Development</option>
                        <option value="ai_solution">AI Solution</option>
                        <option value="system_arch">System Architecture</option>
                    </select>
                     @error('category') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Icon (SVG) -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Icon (SVG Code)</label>
                    <textarea wire:model="icon" rows="3" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2.5 text-white font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"></textarea>
                    <p class="text-xs text-slate-500 mt-1">Paste standard SVG code (w-6 h-6).</p>
                    @error('icon') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg shadow-lg shadow-indigo-500/20 transition">
                {{ $is_edit ? 'Update Service' : 'Create Service' }}
            </button>
        </div>
    </form>
</div>
