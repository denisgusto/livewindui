{{-- Tab: botao de aba controlado pelo wrapper Tabs. Props: name. Uso: <x-livewindui::tab name="profile">Perfil</x-livewindui::tab> --}}
@props([
    'name',
])

@php
    $tabId = 'livewindui-tab-'.preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $name);
    $panelId = 'livewindui-tab-panel-'.preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $name);
@endphp

<button
    type="button"
    id="{{ $tabId }}"
    role="tab"
    x-on:click="active = @js($name)"
    x-bind:aria-selected="(active === @js($name)).toString()"
    aria-controls="{{ $panelId }}"
    x-bind:tabindex="active === @js($name) ? 0 : -1"
    {{ $attributes->class([
        'border-b-2 px-3 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-accent',
        'border-transparent text-gray-600 hover:border-gray-300 hover:text-gray-900 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:text-gray-100',
    ])->merge([
        'x-bind:class' => "{ 'border-accent text-accent-content': active === ".Illuminate\Support\Js::from($name)." }",
    ]) }}
>
    {{ $slot }}
</button>
