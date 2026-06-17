<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders table head rows and cells', function () {
    $html = Blade::render(<<<'BLADE'
        <x-livewindui::table striped>
            <x-slot:head>
                <x-livewindui::table-row>
                    <x-livewindui::table-header>Nome</x-livewindui::table-header>
                    <x-livewindui::table-header>Email</x-livewindui::table-header>
                </x-livewindui::table-row>
            </x-slot:head>

            <x-livewindui::table-row>
                <x-livewindui::table-cell>Ana</x-livewindui::table-cell>
                <x-livewindui::table-cell>ana@example.com</x-livewindui::table-cell>
            </x-livewindui::table-row>
        </x-livewindui::table>
    BLADE);

    expect($html)
        ->toContain('<table')
        ->toContain('<thead')
        ->toContain('<tbody')
        ->toContain('Nome')
        ->toContain('Ana')
        ->toContain('[&amp;_tr:nth-child(even)]:bg-gray-50');
});

it('renders sortable table header state', function () {
    $html = Blade::render('<x-livewindui::table-header sortable sorted direction="desc">Nome</x-livewindui::table-header>');

    expect($html)
        ->toContain('role')
        ->toContain('Nome')
        ->toContain('↓');
});
