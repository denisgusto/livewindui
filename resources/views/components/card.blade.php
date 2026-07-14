{{-- Card: wrapper de conteudo com slots opcionais header/footer. Props: variant=default|bordered|elevated. Uso: <x-livewind::card><x-slot:header>Titulo</x-slot:header>Conteudo</x-livewind::card> --}}
@props([
    'variant' => 'default',
])

@php
    $variantClasses = match ($variant) {
        'bordered' => 'border border-border bg-surface',
        'elevated' => 'border border-border bg-surface shadow-md',
        'default' => 'border border-border bg-surface shadow-sm',
        default => 'border border-border bg-surface shadow-sm',
    };
@endphp

<section {{ $attributes->class(['overflow-hidden rounded-lg', $variantClasses]) }}>
    @isset($header)
        <div class="border-b border-border px-5 py-4">
            {{ $header }}
        </div>
    @endisset

    <div class="px-5 py-4">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="border-t border-border bg-muted px-5 py-3">
            {{ $footer }}
        </div>
    @endisset
</section>
