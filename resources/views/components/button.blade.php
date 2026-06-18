{{-- Button: botao com variantes semanticas (Flux-style) sobre tokens tematizaveis. Dark mode automatico via tokens (sem dark:). Props: variant, size, icon, icon-trailing, loading, confirm, href, square, type. --}}
@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconTrailing' => null,
    'loading' => null,
    'confirm' => null,
    'href' => null,
    'square' => false,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 rounded-md font-medium shadow-sm transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:pointer-events-none disabled:opacity-50';

    // Variantes 100% semanticas: cada uma referencia tokens que ja trocam sob `.dark`.
    // Nenhuma classe `dark:` e necessaria aqui (os tokens neutros fazem o swap).
    $variantClasses = match ($variant) {
        'primary' => 'bg-accent text-accent-foreground hover:bg-accent-content focus-visible:outline-accent',
        'filled', 'secondary' => 'bg-muted text-surface-foreground hover:bg-muted/80 focus-visible:outline-muted-foreground',
        'danger' => 'bg-danger text-danger-foreground hover:bg-danger/90 focus-visible:outline-danger',
        'outline' => 'border border-border bg-surface text-surface-foreground hover:bg-muted focus-visible:outline-muted-foreground',
        'ghost' => 'text-surface-foreground shadow-none hover:bg-muted focus-visible:outline-muted-foreground',
        'subtle' => 'text-muted-foreground shadow-none hover:bg-muted hover:text-surface-foreground focus-visible:outline-muted-foreground',
        default => 'bg-accent text-accent-foreground hover:bg-accent-content focus-visible:outline-accent',
    };

    $sizeClasses = $square
        ? match ($size) {
            'xs' => 'h-7 w-7 text-xs',
            'sm' => 'h-8 w-8 text-sm',
            'lg' => 'h-12 w-12 text-base',
            default => 'h-10 w-10 text-sm',
        }
        : match ($size) {
            'xs' => 'px-2.5 py-1 text-xs',
            'sm' => 'px-3 py-1.5 text-sm',
            'lg' => 'px-5 py-2.5 text-base',
            default => 'px-4 py-2 text-sm',
        };

    $tag = $href ? 'a' : 'button';

    $buttonAttributes = $attributes
        ->class([$baseClasses, $variantClasses, $sizeClasses])
        ->merge([
            'type' => $href ? null : $type,
            'href' => $href,
            'wire:confirm' => $confirm,
            'aria-busy' => $loading ? 'true' : null,
        ]);
@endphp

<{{ $tag }} {{ $buttonAttributes }}>
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

    @if ($iconTrailing)
        <span aria-hidden="true">{{ $iconTrailing }}</span>
    @endif
</{{ $tag }}>
