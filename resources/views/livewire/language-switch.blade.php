<div class="flex items-center gap-0.5 p-0.5 bg-surface-container-high border border-outline-variant/30">
    <button
        wire:click="switch('en')"
        type="button"
        class="px-2.5 py-1 font-label text-[10px] font-semibold uppercase tracking-wider transition-colors duration-blueprint {{ app()->getLocale() === 'en' ? 'bg-primary text-on-primary' : 'text-secondary hover:text-on-background hover:bg-surface-container-low' }}"
        aria-label="English"
    >
        EN
    </button>
    <button
        wire:click="switch('id')"
        type="button"
        class="px-2.5 py-1 font-label text-[10px] font-semibold uppercase tracking-wider transition-colors duration-blueprint {{ app()->getLocale() === 'id' ? 'bg-primary text-on-primary' : 'text-secondary hover:text-on-background hover:bg-surface-container-low' }}"
        aria-label="Indonesian"
    >
        ID
    </button>
</div>
