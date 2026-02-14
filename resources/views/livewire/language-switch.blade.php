<div class="flex items-center gap-1 p-1 rounded-lg bg-slate-800/60 border border-slate-700/60">
    <button
        wire:click="switch('en')"
        class="px-2.5 py-1 text-xs font-medium rounded-md transition {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}"
        aria-label="English"
    >
        EN
    </button>
    <button
        wire:click="switch('id')"
        class="px-2.5 py-1 text-xs font-medium rounded-md transition {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}"
        aria-label="Indonesian"
    >
        ID
    </button>
</div>
