<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders table head rows and cells', function () {
    $html = Blade::render(<<<'BLADE'
        <x-livewind::table striped>
            <x-slot:head>
                <x-livewind::table-row>
                    <x-livewind::table-header>Nome</x-livewind::table-header>
                    <x-livewind::table-header>Email</x-livewind::table-header>
                </x-livewind::table-row>
            </x-slot:head>

            <x-livewind::table-row>
                <x-livewind::table-cell>Ana</x-livewind::table-cell>
                <x-livewind::table-cell>ana@example.com</x-livewind::table-cell>
            </x-livewind::table-row>
        </x-livewind::table>
    BLADE);

    expect($html)
        ->toContain('<table')
        ->toContain('<thead')
        ->toContain('<tbody')
        ->toContain('Nome')
        ->toContain('Ana')
        ->toContain('[&amp;_tr:nth-child(even)]:bg-muted');
});

it('renders sortable table header state', function () {
    $html = Blade::render('<x-livewind::table-header sortable sorted direction="desc">Nome</x-livewind::table-header>');

    expect($html)
        ->toContain('role')
        ->toContain('Nome')
        ->toContain('↓');
});
