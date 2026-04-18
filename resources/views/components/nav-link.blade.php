@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-4 py-2 rounded-xl text-amber-300 bg-amber-400/10 border border-amber-400/30 font-semibold text-sm shadow-[0_0_12px_rgba(251,191,36,0.25)] transition-all duration-200'
    : 'inline-flex items-center px-4 py-2 rounded-xl text-stone-300 text-sm hover:text-white hover:bg-white/5 hover:scale-[1.03] transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
