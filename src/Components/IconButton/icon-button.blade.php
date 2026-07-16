{{-- IconButton: botao quadrado para icones com aria-label obrigatorio. Props: variant=primary|secondary|danger|outline|ghost, size=sm|md|lg, label. Uso: <x-livewind::icon-button label="Editar">...</x-livewind::icon-button> --}}

@php
    $variantClasses = match ($variant) {
        'primary' => 'bg-accent text-accent-foreground hover:bg-accent-content focus-visible:outline-accent',
        'secondary' => 'bg-muted text-surface-foreground hover:bg-muted focus-visible:outline-muted-foreground',
        'danger' => 'bg-danger text-danger-foreground hover:bg-danger focus-visible:outline-danger',
        'outline' => 'border border-border bg-surface text-surface-foreground hover:bg-muted focus-visible:outline-muted-foreground',
        'ghost' => 'text-surface-foreground hover:bg-muted focus-visible:outline-muted-foreground',
        default => 'text-surface-foreground hover:bg-muted focus-visible:outline-muted-foreground',
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
        'aria-label' => $label ?? __('livewind::ui.action'),
    ]) }}
>
    {{ $slot }}
</button>
