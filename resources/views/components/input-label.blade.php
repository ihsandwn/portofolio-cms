@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-label text-[10px] font-semibold uppercase tracking-[0.15em] text-secondary']) }}>
    {{ $value ?? $slot }}
</label>
