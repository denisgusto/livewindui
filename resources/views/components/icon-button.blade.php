{{-- IconButton: botao quadrado para icones com aria-label obrigatorio. Props: variant=primary|secondary|danger|outline|ghost, size=sm|md|lg, label. Uso: <x-livewindui::icon-button label="Editar">...</x-livewindui::icon-button> --}}
@props([
    'variant' => 'ghost',
    'size' => 'md',
    'label' => null,
    'type' => 'button',
])

@php
    $variantClasses = match ($variant) {
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 focus-visible:outline-indigo-600',
        'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200 focus-visible:outline-gray-400',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:outline-red-600',
        'outline' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus-visible:outline-gray-400',
        'ghost' => 'text-gray-700 hover:bg-gray-100 focus-visible:outline-gray-400',
        default => 'text-gray-700 hover:bg-gray-100 focus-visible:outline-gray-400',
    };

    $sizeClasses = match ($size) {
        'sm' => 'h-8 w-8 text-sm',
        'md' => 'h-10 w-10 text-base',
        'lg' => 'h-12 w-12 text-lg',
        default => 'h-10 w-10 text-base',
    };
@endphp

<button
    {{ $attributes->class([
        'inline-flex items-center justify-center rounded-md font-medium shadow-sm transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:pointer-events-none disabled:opacity-50',
        $variantClasses,
        $sizeClasses,
    ])->merge([
        'type' => $type,
        'aria-label' => $label ?? 'Acao',
    ]) }}
>
    {{ $slot }}
</button>
