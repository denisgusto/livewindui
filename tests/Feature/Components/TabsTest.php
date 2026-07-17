<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders tabs with aria roles and alpine state', function () {
    $html = Blade::render(<<<'BLADE'
        <x-livewind::tabs default-tab="profile">
            <x-livewind::tab-list>
                <x-livewind::tab name="profile">Perfil</x-livewind::tab>
                <x-livewind::tab name="security">Seguranca</x-livewind::tab>
            </x-livewind::tab-list>
            <x-livewind::tab-panels>
                <x-livewind::tab-panel name="profile">Dados gerais</x-livewind::tab-panel>
                <x-livewind::tab-panel name="security">Permissoes</x-livewind::tab-panel>
            </x-livewind::tab-panels>
        </x-livewind::tabs>
    BLADE);

    expect($html)
        ->toContain("active: 'profile'", false)
        ->toContain('role="tablist"')
        ->toContain('role="tab"')
        ->toContain('role="tabpanel"')
        ->toContain('x-on:keydown.arrow-right.prevent')
        ->toContain('x-on:keydown.home.prevent')
        ->toContain('target.focus()')
        ->not->toContain('$focus')
        ->toContain('Dados gerais');
});

it('supports server side active tab entangle', function () {
    $html = Blade::render('<x-livewind::tabs default-tab="profile" server-side><x-livewind::tab name="profile">Perfil</x-livewind::tab></x-livewind::tabs>');

    expect($html)->toContain('$wire.entangle(&#039;activeTab&#039;).live');
});
