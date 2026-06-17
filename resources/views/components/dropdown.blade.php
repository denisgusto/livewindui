{{-- Dropdown: menu Alpine sem entangle, com trigger e itens. Props: align, width. Uso: <x-livewindui::dropdown><x-slot:trigger>...</x-slot:trigger>...</x-livewindui::dropdown> --}}
@props([
    'align' => 'right',
    'width' => 'md',
])

@php
    $alignClasses = match ($align) {
        'left' => 'left-0 origin-top-left',
        'center' => 'left-1/2 -translate-x-1/2 origin-top',
        'right' => 'right-0 origin-top-right',
        default => 'right-0 origin-top-right',
    };

    $widthClasses = match ($width) {
        'sm' => 'w-40',
        'md' => 'w-56',
        'lg' => 'w-72',
        'auto' => 'w-auto',
        default => 'w-56',
    };
@endphp

<div
    x-data="{ open: false }"
    x-on:keydown.escape.window="open = false"
    x-on:click.outside.stop="open = false"
    {{ $attributes->class(['relative inline-block text-left']) }}
>
    <div x-on:click="open = ! open" x-bind:aria-expanded="open.toString()" aria-haspopup="true">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-cloak
        x-transition
        role="menu"
        class="absolute z-50 mt-2 rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none {{ $alignClasses }} {{ $widthClasses }}"
    >
        {{ $slot }}
    </div>
</div>
