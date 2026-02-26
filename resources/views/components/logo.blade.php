@props([
    'size' => 'md',
    'withText' => true,
])

@php
    $sizes = [
        'sm' => ['box' => 'w-8 h-8 rounded-xl', 'icon' => 'text-xl', 'text' => 'text-lg'],
        'md' => ['box' => 'w-12 h-12 rounded-2xl', 'icon' => 'text-3xl', 'text' => 'text-xl'],
        'lg' => ['box' => 'w-16 h-16 rounded-[1.8rem]', 'icon' => 'text-4xl', 'text' => 'text-2xl'],
    ];
    $current = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    <div class="{{ $current['box'] }} bg-blue-600 flex items-center justify-center text-white shadow-xl shadow-blue-500/20 rotate-3 hover:rotate-0 transition-all duration-500 shrink-0">
        <i class="fas fa-compass {{ $current['icon'] }}"></i>
    </div>

    @if ($withText)
        <h1 class="{{ $current['text'] }} font-black italic text-blue-600 tracking-tight leading-none">
            VISTORA
        </h1>
    @endif
</div>
