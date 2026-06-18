{{-- Badge: etiqueta compacta com variantes semanticas e dot opcional. Props: variant=success|info|warning|danger|neutral, dot. Uso: <x-livewindui::badge variant="success" dot>Ativo</x-livewindui::badge> --}}
@props([
    'variant' => 'neutral',
    'dot' => false,
])

@php
    $variantClasses = match ($variant) {
        'success' => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-950/50 dark:text-green-300 dark:ring-green-400/20',
        'info' => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-950/50 dark:text-blue-300 dark:ring-blue-400/20',
        'warning' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20 dark:bg-yellow-950/50 dark:text-yellow-300 dark:ring-yellow-400/20',
        'danger' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-950/50 dark:text-red-300 dark:ring-red-400/20',
        'neutral' => 'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-400/20',
        default => 'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-400/20',
    };

    $dotClasses = match ($variant) {
        'success' => 'bg-green-500',
        'info' => 'bg-blue-500',
        'warning' => 'bg-yellow-500',
        'danger' => 'bg-red-500',
        'neutral' => 'bg-gray-400',
        default => 'bg-gray-400',
    };
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset', $variantClasses]) }}>
    @if ($dot)
        <span class="h-1.5 w-1.5 rounded-full {{ $dotClasses }}" aria-hidden="true"></span>
    @endif

    {{ $slot }}
</span>
