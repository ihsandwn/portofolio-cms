<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-primary border border-transparent font-label text-xs text-on-primary uppercase tracking-widest hover:bg-primary-dim focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-surface-container-lowest active:bg-primary-dim disabled:opacity-25 transition-colors duration-blueprint']) }}>
    {{ $slot }}
</button>
