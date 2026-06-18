{{-- Card: wrapper de conteudo com slots opcionais header/footer. Props: variant=default|bordered|elevated. Uso: <x-livewindui::card><x-slot:header>Titulo</x-slot:header>Conteudo</x-livewindui::card> --}}
@props([
    'variant' => 'default',
])

@php
    $variantClasses = match ($variant) {
        'bordered' => 'border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900',
        'elevated' => 'border border-gray-100 bg-white shadow-md dark:border-gray-800 dark:bg-gray-900',
        'default' => 'border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900',
        default => 'border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900',
    };
@endphp

<section {{ $attributes->class(['overflow-hidden rounded-lg', $variantClasses]) }}>
    @isset($header)
        <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800">
            {{ $header }}
        </div>
    @endisset

    <div class="px-5 py-4">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="border-t border-gray-200 bg-gray-50 px-5 py-3 dark:border-gray-800 dark:bg-gray-800/40">
            {{ $footer }}
        </div>
    @endisset
</section>
