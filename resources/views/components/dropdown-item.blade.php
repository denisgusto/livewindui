{{-- DropdownItem: item de menu como button ou link. Props: href, type. Uso: <x-livewindui::dropdown-item wire:click="edit">Editar</x-livewindui::dropdown-item> --}}
@props([
    'href' => null,
    'type' => 'button',
])

@php
    $classes = 'block w-full px-4 py-2 text-left text-sm text-gray-700 transition hover:bg-gray-100 hover:text-gray-950 focus:bg-gray-100 focus:outline-none';
@endphp

@if ($href)
    <a {{ $attributes->class([$classes])->merge(['href' => $href, 'role' => 'menuitem']) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->class([$classes])->merge(['type' => $type, 'role' => 'menuitem']) }}>
        {{ $slot }}
    </button>
@endif
