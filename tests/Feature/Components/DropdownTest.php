<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders dropdown trigger menu and behavior', function () {
    $html = Blade::render(<<<'BLADE'
        <x-livewind::dropdown align="left" width="sm">
            <x-slot:trigger>
                <button type="button">Acoes</button>
            </x-slot:trigger>

            <x-livewind::dropdown-item wire:click="edit">Editar</x-livewind::dropdown-item>
            <x-livewind::dropdown-item href="/contatos">Abrir</x-livewind::dropdown-item>
        </x-livewind::dropdown>
    BLADE);

    expect($html)
        ->toContain('x-data="{ open: false }"')
        ->toContain('x-on:keydown.escape.window')
        ->toContain('x-on:click.outside.stop')
        ->toContain('role="menu"')
        ->toContain('role="menuitem"')
        ->toContain('Editar')
        ->toContain('href="/contatos"')
        ->toContain('left-0 origin-top-left')
        ->toContain('w-40');
});
