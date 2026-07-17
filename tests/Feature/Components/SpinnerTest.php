<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders an animated spinner svg', function () {
    $html = Blade::render('<x-livewind::spinner />');

    expect($html)
        ->toContain('<svg')
        ->toContain('animate-spin')
        ->toContain('h-5 w-5')
        ->toContain('aria-hidden="true"');
});

it('applies size classes', function (string $size, string $expected) {
    $html = Blade::render("<x-livewind::spinner size=\"{$size}\" />");

    expect($html)->toContain($expected);
})->with([
    'sm' => ['sm', 'h-4 w-4'],
    'md' => ['md', 'h-5 w-5'],
    'lg' => ['lg', 'h-6 w-6'],
]);

it('merges consumer classes', function () {
    $html = Blade::render('<x-livewind::spinner class="text-accent" />');

    expect($html)
        ->toContain('text-accent')
        ->toContain('animate-spin');
});
