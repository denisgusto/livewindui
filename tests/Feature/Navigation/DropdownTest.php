<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders dropdown trigger menu and behavior', function () {
    $html = Blade::render(<<<'BLADE'
        <x-livewindui::dropdown align="left" width="sm">
            <x-slot:trigger>
                <button type="button">Acoes</button>
            </x-slot:trigger>

            <x-livewindui::dropdown-item wire:click="edit">Editar</x-livewindui::dropdown-item>
            <x-livewindui::dropdown-item href="/contatos">Abrir</x-livewindui::dropdown-item>
        </x-livewindui::dropdown>
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
