{{-- Badge: etiqueta compacta com variantes semanticas e dot opcional. Props: variant=success|info|warning|danger|neutral, dot. Uso: <x-livewind::badge variant="success" dot>Ativo</x-livewind::badge> --}}
@props([
    'variant' => 'neutral',
    'dot' => false,
])

@php
    $variantClasses = match ($variant) {
        'success' => 'bg-success/10 text-success ring-success/20',
        'info' => 'bg-info/10 text-info ring-info/20',
        'warning' => 'bg-warning/10 text-warning ring-warning/20',
        'danger' => 'bg-danger/10 text-danger ring-danger/20',
        'neutral' => 'bg-muted text-surface-foreground ring-border',
        default => 'bg-muted text-surface-foreground ring-border',
    };

    $dotClasses = match ($variant) {
        'success' => 'bg-success',
        'info' => 'bg-info',
        'warning' => 'bg-warning',
        'danger' => 'bg-danger',
        'neutral' => 'bg-muted-foreground',
        default => 'bg-muted-foreground',
    };
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset', $variantClasses]) }}>
    @if ($dot)
        <span class="h-1.5 w-1.5 rounded-full {{ $dotClasses }}" aria-hidden="true"></span>
    @endif

    {{ $slot }}
</span>
