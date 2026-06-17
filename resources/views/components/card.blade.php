{{-- Card: wrapper de conteudo com slots opcionais header/footer. Props: variant=default|bordered|elevated. Uso: <x-livewindui::card><x-slot:header>Titulo</x-slot:header>Conteudo</x-livewindui::card> --}}
@props([
    'variant' => 'default',
])

@php
    $variantClasses = match ($variant) {
        'bordered' => 'border border-gray-200 bg-white',
        'elevated' => 'border border-gray-100 bg-white shadow-md',
        'default' => 'border border-gray-200 bg-white shadow-sm',
        default => 'border border-gray-200 bg-white shadow-sm',
    };
@endphp

<section {{ $attributes->class(['overflow-hidden rounded-lg', $variantClasses]) }}>
    @isset($header)
        <div class="border-b border-gray-200 px-5 py-4">
            {{ $header }}
        </div>
    @endisset

    <div class="px-5 py-4">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="border-t border-gray-200 bg-gray-50 px-5 py-3">
            {{ $footer }}
        </div>
    @endisset
</section>
