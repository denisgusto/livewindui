{{-- Tabs: estado de abas client-side ou Livewire server-side. Props: defaultTab, serverSide. Uso: <x-livewind::tabs default-tab="profile">...</x-livewind::tabs> --}}

@php
    $activeState = $serverSide
        ? "\$wire.entangle('activeTab').live"
        : Illuminate\Support\Js::from($defaultTab);
@endphp

<div
    x-data="{
        active: {{ $activeState }},
        move(event) {
            const tabs = [...this.$el.querySelectorAll('[role=tab]')];
            const current = tabs.indexOf(event.target);
            if (current < 0) return;

            const target = event.key === 'Home'
                ? tabs[0]
                : event.key === 'End'
                    ? tabs[tabs.length - 1]
                    : tabs[(current + (event.key === 'ArrowRight' ? 1 : -1) + tabs.length) % tabs.length];

            target.focus();
            target.click();
        },
    }"
    x-on:keydown.arrow-right.prevent="move($event)"
    x-on:keydown.arrow-left.prevent="move($event)"
    x-on:keydown.home.prevent="move($event)"
    x-on:keydown.end.prevent="move($event)"
    {{ $attributes->class(['space-y-4']) }}
>
    {{ $slot }}
</div>
