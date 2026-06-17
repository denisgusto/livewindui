{{-- TabPanel: painel associado a uma aba. Props: name. Uso: <x-livewindui::tab-panel name="profile">...</x-livewindui::tab-panel> --}}
@props([
    'name',
])

@php
    $tabId = 'livewindui-tab-'.preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $name);
    $panelId = 'livewindui-tab-panel-'.preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $name);
@endphp

<div
    id="{{ $panelId }}"
    role="tabpanel"
    aria-labelledby="{{ $tabId }}"
    x-show="active === @js($name)"
    x-cloak
    {{ $attributes->class(['rounded-lg bg-white']) }}
>
    {{ $slot }}
</div>
