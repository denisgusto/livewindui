{{-- DropdownItem: item de menu como button ou link. Props: href, type. Uso: <x-livewind::dropdown-item wire:click="edit">Editar</x-livewind::dropdown-item> --}}

@php
    $classes = 'block w-full px-4 py-2 text-left text-sm text-surface-foreground transition hover:bg-muted hover:text-surface-foreground focus:bg-muted focus:outline-none';
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
