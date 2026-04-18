@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'mt-2 w-full rounded-2xl border border-white/10 bg-stone-900 px-4 py-3 text-sm text-white placeholder:text-stone-500 focus:border-amber-400 focus:outline-none focus:ring-0 transition duration-200 ease-in-out'
    ]) }}
>
