<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders tabs with aria roles and alpine state', function () {
    $html = Blade::render(<<<'BLADE'
        <x-livewindui::tabs default-tab="profile">
            <x-livewindui::tab-list>
                <x-livewindui::tab name="profile">Perfil</x-livewindui::tab>
                <x-livewindui::tab name="security">Seguranca</x-livewindui::tab>
            </x-livewindui::tab-list>
            <x-livewindui::tab-panels>
                <x-livewindui::tab-panel name="profile">Dados gerais</x-livewindui::tab-panel>
                <x-livewindui::tab-panel name="security">Permissoes</x-livewindui::tab-panel>
            </x-livewindui::tab-panels>
        </x-livewindui::tabs>
    BLADE);

    expect($html)
        ->toContain('x-data="{ active: \'profile\' }"', false)
        ->toContain('role="tablist"')
        ->toContain('role="tab"')
        ->toContain('role="tabpanel"')
        ->toContain('x-on:keydown.arrow-right.prevent')
        ->toContain('Dados gerais');
});

it('supports server side active tab entangle', function () {
    $html = Blade::render('<x-livewindui::tabs default-tab="profile" server-side><x-livewindui::tab name="profile">Perfil</x-livewindui::tab></x-livewindui::tabs>');

    expect($html)->toContain('$wire.entangle(&#039;activeTab&#039;).live');
});
