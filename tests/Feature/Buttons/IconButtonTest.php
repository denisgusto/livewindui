<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders icon button with aria label', function () {
    $html = Blade::render('<x-livewindui::icon-button label="Editar">E</x-livewindui::icon-button>');

    expect($html)
        ->toContain('<button')
        ->toContain('aria-label="Editar"')
        ->toContain('h-10 w-10')
        ->toContain('E');
});

it('applies icon button variants and sizes', function (string $variant, string $size, string $expectedVariant, string $expectedSize) {
    $html = Blade::render("<x-livewindui::icon-button label=\"Acao\" variant=\"{$variant}\" size=\"{$size}\">X</x-livewindui::icon-button>");

    expect($html)
        ->toContain($expectedVariant)
        ->toContain($expectedSize);
})->with([
    'primary sm' => ['primary', 'sm', 'bg-indigo-600', 'h-8 w-8'],
    'secondary md' => ['secondary', 'md', 'bg-gray-100', 'h-10 w-10'],
    'danger lg' => ['danger', 'lg', 'bg-red-600', 'h-12 w-12'],
]);

it('merges icon button attributes', function () {
    $html = Blade::render('<x-livewindui::icon-button label="Fechar" class="extra" data-test="icon">X</x-livewindui::icon-button>');

    expect($html)
        ->toContain('extra')
        ->toContain('data-test="icon"');
});
