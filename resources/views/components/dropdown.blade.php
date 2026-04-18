@props([
    'align' => 'right',
    'width' => '56'
])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-48',
    '56' => 'w-56',
    default => $width,
};
@endphp

<div class="relative z-50" x-data="{ open: false }" @click.outside="open = false">

    <!-- TRIGGER -->
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    <!-- DROPDOWN -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute {{ $alignmentClasses }} mt-3 {{ $width }}"
        style="display: none;"
    >
        <div class="
            rounded-2xl
            bg-stone-900/90
            backdrop-blur-xl
            border border-white/10
            shadow-[0_20px_60px_rgba(0,0,0,0.7)]
            overflow-hidden
        ">
            {{ $content }}
        </div>
    </div>

</div>
