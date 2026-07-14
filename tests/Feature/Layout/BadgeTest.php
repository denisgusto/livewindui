<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders badge with default variant', function () {
    $html = Blade::render('<x-livewind::badge>Neutral</x-livewind::badge>');

    expect($html)
        ->toContain('Neutral')
        ->toContain('bg-muted')
        ->toContain('ring-1');
});

it('applies badge variants', function (string $variant, string $expectedClass) {
    $html = Blade::render("<x-livewind::badge variant=\"{$variant}\" dot>Status</x-livewind::badge>");

    expect($html)
        ->toContain($expectedClass)
        ->toContain('h-1.5 w-1.5');
})->with([
    'success' => ['success', 'bg-success/10'],
    'info' => ['info', 'bg-info/10'],
    'warning' => ['warning', 'bg-warning/10'],
    'danger' => ['danger', 'bg-danger/10'],
    'neutral' => ['neutral', 'bg-muted'],
]);

it('merges badge attributes', function () {
    $html = Blade::render('<x-livewind::badge class="extra" data-test="badge">X</x-livewind::badge>');

    expect($html)
        ->toContain('extra')
        ->toContain('data-test="badge"');
});
