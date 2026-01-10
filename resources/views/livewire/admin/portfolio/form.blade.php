<div>
    <form wire:submit="save" class="max-w-4xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
            <h2 class="text-lg font-semibold text-slate-100 mb-4">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Project Title</label>
                    <input wire:model.live="title" type="text" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                    @error('title') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Slug</label>
                    <input wire:model="slug" type="text" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                    @error('slug') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Project Type</label>
                    <select wire:model="type" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                        <option value="web">Web Development</option>
                        <option value="ai_agent">AI Agent / Automation</option>
                        <option value="consulting">Consulting / Architecture</option>
                    </select>
                </div>

                <!-- Client -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Client Name</label>
                    <input wire:model="client" type="text" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            
            <!-- Description -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-400 mb-1">Short Description</label>
                <textarea wire:model="description" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-slate-100 focus:ring-2 focus:ring-indigo-500"></textarea>
                @error('description') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Technical Details -->
        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
            <h2 class="text-lg font-semibold text-slate-100 mb-4">Technical Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- URL -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Project URL</label>
                    <input wire:model="url" type="url" placeholder="https://" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                    @error('url') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Repo URL -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Repository URL</label>
                    <input wire:model="repo_url" type="url" placeholder="https://github.com/..." class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <!-- Tech Stack -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-400 mb-1">Tech Stack (Comma users separated)</label>
                <input wire:model="tech_stack_input" type="text" placeholder="Laravel, Vue.js, Tailwind, OpenAI" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                <p class="text-xs text-slate-500 mt-1">Separate technologies with commas.</p>
            </div>
        </div>

        <!-- Case Study -->
        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
            <h2 class="text-lg font-semibold text-slate-100 mb-4">Case Study (Markdown)</h2>
            <textarea wire:model="case_study" rows="10" placeholder="## Problem..." class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-slate-100 font-mono text-sm focus:ring-2 focus:ring-indigo-500"></textarea>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between bg-slate-800 p-6 rounded-lg border border-slate-700">
            <label class="flex items-center space-x-3 cursor-pointer">
                <input wire:model="is_featured" type="checkbox" class="form-checkbox h-5 w-5 text-indigo-600 rounded bg-slate-900 border-slate-700 focus:ring-indigo-500">
                <span class="text-slate-300 font-medium">Feature on Homepage</span>
            </label>

            <div class="flex space-x-4">
                <a href="{{ route('admin.portfolios.index') }}" class="px-6 py-2 border border-slate-600 rounded-lg text-slate-300 hover:bg-slate-700 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg transition shadow-lg shadow-indigo-500/20">
                    {{ $is_edit ? 'Update Portfolio' : 'Create Portfolio' }}
                </button>
            </div>
        </div>

    </form>
</div>
