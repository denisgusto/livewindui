@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'loading' => null,
    'confirm' => null,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 rounded-md font-medium shadow-sm transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:pointer-events-none disabled:opacity-50';

    $variantClasses = config("livewindui.button.variants.{$variant}") ?? match ($variant) {
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 focus-visible:outline-indigo-600',
        'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200 focus-visible:outline-gray-400',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:outline-red-600',
        'outline' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus-visible:outline-gray-400',
        'ghost' => 'text-gray-700 hover:bg-gray-100 focus-visible:outline-gray-400',
        default => 'bg-indigo-600 text-white hover:bg-indigo-700 focus-visible:outline-indigo-600',
    };

    $sizeClasses = config("livewindui.button.sizes.{$size}") ?? match ($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
        default => 'px-4 py-2 text-sm',
    };

    $buttonAttributes = $attributes
        ->class([$baseClasses, $variantClasses, $sizeClasses])
        ->merge([
            'type' => $type,
            'wire:confirm' => $confirm,
            'aria-busy' => $loading ? 'true' : null,
        ]);
@endphp

<button {{ $buttonAttributes }}>
    @if ($icon)
        <span aria-hidden="true">{{ $icon }}</span>
    @endif

    @if ($loading)
        <span wire:loading wire:target="{{ $loading }}" class="contents">
            <x-livewindui::spinner size="sm" />
        </span>
        <span wire:loading.remove wire:target="{{ $loading }}" class="contents">
            {{ $slot }}
        </span>
    @else
        {{ $slot }}
    @endif
</button>
