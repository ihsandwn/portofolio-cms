<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-transparent border border-outline font-label text-xs text-on-background uppercase tracking-widest hover:bg-surface-container-low focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-surface-container-lowest disabled:opacity-25 transition-colors duration-blueprint']) }}>
    {{ $slot }}
</button>
