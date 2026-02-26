@props([
    'icon' => null,
    'iconPosition' => 'left',
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'loading' => false,
    'disableOnLoading' => false,
])

@php
    $tag = $href ? 'a' : 'button';
    $customClasses = $attributes->get('class', '');
    $hasCustomPadding = preg_match('/\b(p|px|py|pt|pb|pl|pr)-(?:\d+|\[.*?\])\b/', $customClasses);
    $hasCustomBg = preg_match('/\bbg-\S+/', $customClasses);

    $variantClass = '';
    if (! $hasCustomBg) {
        $variantClass = match ($variant) {
            'secondary' => 'bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800',
            'success' => 'bg-emerald-600 text-white hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600',
            'danger' => 'bg-rose-500 text-white hover:bg-rose-600',
            'ghost' => 'bg-transparent hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400',
            default => 'bg-blue-600 text-white hover:bg-blue-700',
        };
    }

    $textSizeClass = match ($size) {
        'sm' => 'text-xs',
        'lg' => 'text-base',
        default => 'text-sm',
    };

    $sizeClass = '';
    if (! $hasCustomPadding) {
        $sizeClass = match ($size) {
            'sm' => 'px-3 py-1.5',
            'lg' => 'px-6 py-3.5',
            default => 'px-5 py-2.5',
        };
    }

    $iconPositionClass = match ($iconPosition) {
        'right' => 'flex-row-reverse',
        'top' => 'flex-col',
        'bottom' => 'flex-col-reverse',
        default => 'flex-row',
    };

    $interactionClass = $loading && ! $disableOnLoading ? 'pointer-events-none' : '';

    $baseClass = 'inline-flex items-center justify-center gap-2 font-semibold rounded-2xl transition-all duration-200 active:scale-95 cursor-pointer disabled:opacity-50 disabled:pointer-events-none';
    $finalClass = trim(preg_replace('/\s+/', ' ', implode(' ', [$baseClass, $iconPositionClass, $variantClass, $textSizeClass, $sizeClass, $interactionClass, $customClasses])));

    $iconSizeClass = $size === 'sm' ? 'text-base' : 'text-lg';
    $spinnerSizeClass = match ($size) {
        'sm' => 'w-3.5 h-3.5',
        'lg' => 'w-5 h-5',
        default => 'w-4 h-4',
    };
@endphp

<{{ $tag }}
    @if ($href)
        href="{{ $href }}"
        @if ($loading && $disableOnLoading) aria-disabled="true" @endif
    @else
        type="{{ $attributes->get('type', 'button') }}"
        @if ($loading && $disableOnLoading) disabled @endif
    @endif
    {{ $attributes->except('class')->merge(['class' => $finalClass]) }}
>
    @if ($loading)
        <svg class="animate-spin {{ $spinnerSizeClass }}" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
        </svg>
    @else
        @if ($icon)
            <i class="ti ti-{{ $icon }} {{ $iconSizeClass }}"></i>
        @endif
    @endif

    @if ($slot->isNotEmpty())
        <span>{{ $slot }}</span>
    @endif
</{{ $tag }}>
