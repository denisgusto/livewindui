{{-- Tabs: estado de abas client-side ou Livewire server-side. Props: defaultTab, serverSide. Uso: <x-livewindui::tabs default-tab="profile">...</x-livewindui::tabs> --}}
@props([
    'defaultTab' => 'default',
    'serverSide' => false,
])

@php
    $activeState = $serverSide
        ? "\$wire.entangle('activeTab').live"
        : Illuminate\Support\Js::from($defaultTab);
@endphp

<div
    x-data="{ active: {{ $activeState }} }"
    x-on:keydown.arrow-right.prevent="$focus.wrap().next()"
    x-on:keydown.arrow-left.prevent="$focus.wrap().previous()"
    {{ $attributes->class(['space-y-4']) }}
>
    {{ $slot }}
</div>
