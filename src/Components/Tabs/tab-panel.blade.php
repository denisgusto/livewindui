{{-- TabPanel: painel associado a uma aba. Props: name. Uso: <x-livewind::tab-panel name="profile">...</x-livewind::tab-panel> --}}

@php
    $tabId = 'livewind-tab-'.preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $name);
    $panelId = 'livewind-tab-panel-'.preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $name);
@endphp

<div
    id="{{ $panelId }}"
    role="tabpanel"
    aria-labelledby="{{ $tabId }}"
    x-show="active === @js($name)"
    x-cloak
    {{ $attributes->class(['rounded-lg bg-surface']) }}
>
    {{ $slot }}
</div>
