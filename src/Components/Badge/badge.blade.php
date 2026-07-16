{{-- Badge: etiqueta compacta com variantes semanticas e dot opcional. Props: variant=success|info|warning|danger|neutral, dot. Uso: <x-livewind::badge variant="success" dot>Ativo</x-livewind::badge> --}}
@props([])

<span {{ $attributes->class($classes()) }}>
    @if ($hasDot())
        @if ($isPing())
            <span class="relative flex {{ $dotSize() }}" aria-hidden="true">
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full {{ $dotColor() }} opacity-75"></span>
                <span class="relative inline-flex {{ $dotSize() }} rounded-full {{ $dotColor() }}"></span>
            </span>
        @else
            <span @class([$dotSize(), 'rounded-full', $dotColor(), 'animate-pulse' => $isPulse()]) aria-hidden="true"></span>
        @endif
    @endif

    @if ($icon)
        <x-dynamic-component :component="'heroicon-m-' . $icon" :class="$iconSize() . ' shrink-0'" />
    @endif

    {{ $slot }}
</span>
