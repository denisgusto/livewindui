{{-- Container: limita largura de secoes com padding responsivo. Props: size=sm|md|lg|xl|full. Uso: <x-livewindui::container size="lg">...</x-livewindui::container> --}}
@props([
    'size' => 'lg',
])

@php
    $sizeClasses = match ($size) {
        'sm' => 'max-w-3xl',
        'md' => 'max-w-5xl',
        'lg' => 'max-w-6xl',
        'xl' => 'max-w-7xl',
        'full' => 'max-w-none',
        default => 'max-w-6xl',
    };
@endphp

<div {{ $attributes->class(['mx-auto w-full px-4 sm:px-6 lg:px-8', $sizeClasses]) }}>
    {{ $slot }}
</div>
