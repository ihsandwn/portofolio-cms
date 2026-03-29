@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-primary font-headline text-xs uppercase tracking-widest text-on-background focus:outline-none transition-colors duration-blueprint'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent font-headline text-xs uppercase tracking-widest text-secondary hover:text-on-background hover:border-outline-variant/50 focus:outline-none transition-colors duration-blueprint';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
