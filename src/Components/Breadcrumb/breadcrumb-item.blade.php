{{-- Breadcrumb item: item de breadcrumb com href opcional e current. Props: href, current. Uso: <x-livewind::breadcrumb-item current>Atual</x-livewind::breadcrumb-item> --}}

<li {{ $attributes->class(['flex items-center gap-2']) }}>
    @if ($href && ! $current)
        <a href="{{ $href }}" class="font-medium text-surface-foreground transition hover:text-surface-foreground focus:outline-none focus:ring-2 focus:ring-accent">
            {{ $slot }}
        </a>
    @else
        <span class="font-medium text-surface-foreground" @if ($current) aria-current="page" @endif>
            {{ $slot }}
        </span>
    @endif

    @if (! $current)
        <span aria-hidden="true" class="text-muted-foreground">/</span>
    @endif
</li>
