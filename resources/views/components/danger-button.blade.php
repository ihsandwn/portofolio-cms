<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-error border border-transparent font-label text-xs text-on-error uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-error focus:ring-offset-2 focus:ring-offset-surface-container-lowest active:opacity-100 disabled:opacity-25 transition-opacity duration-blueprint']) }}>
    {{ $slot }}
</button>
