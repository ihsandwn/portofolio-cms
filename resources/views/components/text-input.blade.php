@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block w-full border-0 border-b border-outline bg-surface-container-lowest text-on-background placeholder:text-on-surface-variant focus:border-b-2 focus:border-primary focus:ring-0 transition-[border-width] duration-blueprint disabled:opacity-50']) }}>
