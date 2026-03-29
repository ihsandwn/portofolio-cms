@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-2 border-primary text-start font-headline text-sm uppercase tracking-wider text-on-background bg-surface-container-low focus:outline-none transition-colors duration-blueprint'
            : 'block w-full ps-3 pe-4 py-2 border-l-2 border-transparent text-start font-headline text-sm uppercase tracking-wider text-secondary hover:text-on-background hover:bg-surface-container-low focus:outline-none transition-colors duration-blueprint';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
