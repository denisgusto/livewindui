<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders card with header footer and body', function () {
    $html = Blade::render(<<<'BLADE'
        <x-livewind::card>
            <x-slot:header>Titulo</x-slot:header>
            Corpo
            <x-slot:footer>Rodape</x-slot:footer>
        </x-livewind::card>
    BLADE);

    expect($html)
        ->toContain('<section')
        ->toContain('Titulo')
        ->toContain('Corpo')
        ->toContain('Rodape')
        ->toContain('shadow-sm');
});

it('applies card variants', function (string $variant, string $expectedClass) {
    $html = Blade::render("<x-livewind::card variant=\"{$variant}\">X</x-livewind::card>");

    expect($html)->toContain($expectedClass);
})->with([
    'default' => ['default', 'shadow-sm'],
    'bordered' => ['bordered', 'border-border'],
    'elevated' => ['elevated', 'shadow-md'],
]);

it('merges card attributes', function () {
    $html = Blade::render('<x-livewind::card class="extra" data-test="card">X</x-livewind::card>');

    expect($html)
        ->toContain('extra')
        ->toContain('data-test="card"');
});
