<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders responsive container', function () {
    $html = Blade::render('<x-livewindui::container>Conteudo</x-livewindui::container>');

    expect($html)
        ->toContain('Conteudo')
        ->toContain('max-w-6xl')
        ->toContain('mx-auto');
});

it('applies container sizes', function (string $size, string $expectedClass) {
    $html = Blade::render("<x-livewindui::container size=\"{$size}\">X</x-livewindui::container>");

    expect($html)->toContain($expectedClass);
})->with([
    'sm' => ['sm', 'max-w-3xl'],
    'md' => ['md', 'max-w-5xl'],
    'lg' => ['lg', 'max-w-6xl'],
    'xl' => ['xl', 'max-w-7xl'],
    'full' => ['full', 'max-w-none'],
]);
