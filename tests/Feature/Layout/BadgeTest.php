<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders badge with default variant', function () {
    $html = Blade::render('<x-livewindui::badge>Neutral</x-livewindui::badge>');

    expect($html)
        ->toContain('Neutral')
        ->toContain('bg-gray-50')
        ->toContain('ring-1');
});

it('applies badge variants', function (string $variant, string $expectedClass) {
    $html = Blade::render("<x-livewindui::badge variant=\"{$variant}\" dot>Status</x-livewindui::badge>");

    expect($html)
        ->toContain($expectedClass)
        ->toContain('h-1.5 w-1.5');
})->with([
    'success' => ['success', 'bg-green-50'],
    'info' => ['info', 'bg-blue-50'],
    'warning' => ['warning', 'bg-yellow-50'],
    'danger' => ['danger', 'bg-red-50'],
    'neutral' => ['neutral', 'bg-gray-50'],
]);

it('merges badge attributes', function () {
    $html = Blade::render('<x-livewindui::badge class="extra" data-test="badge">X</x-livewindui::badge>');

    expect($html)
        ->toContain('extra')
        ->toContain('data-test="badge"');
});
