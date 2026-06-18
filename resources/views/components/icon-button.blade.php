{{-- IconButton: botao quadrado para icones com aria-label obrigatorio. Props: variant=primary|secondary|danger|outline|ghost, size=sm|md|lg, label. Uso: <x-livewindui::icon-button label="Editar">...</x-livewindui::icon-button> --}}
@props([
    'variant' => 'ghost',
    'size' => 'md',
    'label' => null,
    'type' => 'button',
])

@php
    $variantClasses = match ($variant) {
        'primary' => 'bg-accent text-accent-foreground hover:bg-accent-content focus-visible:outline-accent',
        'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200 focus-visible:outline-gray-400 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:outline-red-600 dark:bg-red-500 dark:hover:bg-red-400',
        'outline' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus-visible:outline-gray-400 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800',
        'ghost' => 'text-gray-700 hover:bg-gray-100 focus-visible:outline-gray-400 dark:text-gray-200 dark:hover:bg-gray-800',
        default => 'text-gray-700 hover:bg-gray-100 focus-visible:outline-gray-400 dark:text-gray-200 dark:hover:bg-gray-800',
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
