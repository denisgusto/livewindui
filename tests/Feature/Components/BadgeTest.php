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

it('renders a static dot without animation classes', function () {
    $html = Blade::render('<x-livewind::badge variant="success" dot>Online</x-livewind::badge>');

    expect($html)
        ->toContain('h-1.5 w-1.5')
        ->toContain('bg-success')
        ->not->toContain('animate-pulse')
        ->not->toContain('animate-ping');
});

it('animates the dot with pulse', function () {
    $html = Blade::render('<x-livewind::badge variant="success" dot="pulse">Online</x-livewind::badge>');

    expect($html)
        ->toContain('animate-pulse')
        ->not->toContain('animate-ping');
});

it('animates the dot with ping', function () {
    $html = Blade::render('<x-livewind::badge variant="success" dot="ping">Online</x-livewind::badge>');

    expect($html)
        ->toContain('animate-ping')
        ->toContain('opacity-75')
        ->not->toContain('animate-pulse');
});

it('hides the dot when dot is false', function () {
    $html = Blade::render('<x-livewind::badge variant="success">No dot</x-livewind::badge>');

    expect($html)
        ->not->toContain('animate-ping')
        ->not->toContain('rounded-full h-1.5');
});

it('applies size classes', function (string $size, string $expectedClass) {
    $html = Blade::render("<x-livewind::badge size=\"{$size}\">X</x-livewind::badge>");

    expect($html)->toContain($expectedClass);
})->with([
    'xs' => ['xs', 'text-[0.625rem]'],
    'sm' => ['sm', 'py-0.5'],
    'md' => ['md', 'px-2 py-1'],
    'lg' => ['lg', 'text-sm'],
]);

it('scales the dot with the badge size', function () {
    $lg = Blade::render('<x-livewind::badge size="lg" dot>Big</x-livewind::badge>');
    $xs = Blade::render('<x-livewind::badge size="xs" dot>Small</x-livewind::badge>');

    expect($lg)->toContain('h-2 w-2');
    expect($xs)->toContain('h-1 w-1');
});

it('renders a heroicon svg when the icon prop is set', function () {
    $html = Blade::render('<x-livewind::badge variant="success" icon="check-circle">Verificado</x-livewind::badge>');

    expect($html)
        ->toContain('<svg')
        ->toContain('Verificado');
});

it('merges badge attributes', function () {
    $html = Blade::render('<x-livewind::badge class="extra" data-test="badge">X</x-livewind::badge>');

    expect($html)
        ->toContain('extra')
        ->toContain('data-test="badge"');
});
