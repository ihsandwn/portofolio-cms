<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-100">{{ $page->exists ? 'Edit Page' : 'Create Page' }}</h1>
        <a href="{{ route('admin.pages.index') }}" class="text-slate-400 hover:text-white transition text-sm">Back to Pages</a>
    </div>

    <form wire:submit="save" class="space-y-8" x-data="{ lang: 'en' }">
        <!-- Language Switcher -->
        <div class="flex space-x-1 bg-slate-800 p-1 rounded-lg w-fit border border-slate-700">
            <button type="button" @click="lang = 'en'" :class="lang === 'en' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white'" class="px-4 py-2 font-medium rounded transition">English</button>
            <button type="button" @click="lang = 'id'" :class="lang === 'id' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white'" class="px-4 py-2 font-medium rounded transition">Indonesia</button>
        </div>

        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 space-y-6">
            <!-- Non-Translatable Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Slug</label>
                    <input type="text" wire:model="slug" class="w-full bg-slate-900 border border-slate-700 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center pt-6">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="form-checkbox h-5 w-5 text-indigo-600 rounded border-slate-700 bg-slate-900 focus:ring-indigo-500">
                        <span class="text-slate-300">Active</span>
                    </label>
                </div>
            </div>

            <!-- Translatable Fields -->
            <div x-show="lang === 'en'" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Title (EN)</label>
                    <input type="text" wire:model.live="title.en" class="w-full bg-slate-900 border border-slate-700 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    @error('title.en') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Content (EN)</label>
                    <textarea wire:model="content.en" rows="10" class="w-full bg-slate-900 border border-slate-700 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono"></textarea>
                    <p class="text-xs text-slate-500 mt-1">HTML is supported.</p>
                    @error('content.en') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div x-show="lang === 'id'" class="space-y-6" style="display: none;">
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Title (ID)</label>
                    <input type="text" wire:model="title.id" class="w-full bg-slate-900 border border-slate-700 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Content (ID)</label>
                    <textarea wire:model="content.id" rows="10" class="w-full bg-slate-900 border border-slate-700 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono"></textarea>
                    <p class="text-xs text-slate-500 mt-1">HTML is supported.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-lg transition">Save Page</button>
        </div>
    </form>
</div>
