<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders breadcrumb navigation', function () {
    $html = Blade::render(<<<'BLADE'
        <x-livewind::breadcrumb>
            <x-livewind::breadcrumb-item href="/">Home</x-livewind::breadcrumb-item>
            <x-livewind::breadcrumb-item current>Atual</x-livewind::breadcrumb-item>
        </x-livewind::breadcrumb>
    BLADE);

    expect($html)
        ->toContain('aria-label="Breadcrumb"')
        ->toContain('href="/"')
        ->toContain('aria-current="page"')
        ->toContain('Home')
        ->toContain('Atual');
});

it('merges breadcrumb attributes', function () {
    $html = Blade::render('<x-livewind::breadcrumb class="extra"><x-livewind::breadcrumb-item current>X</x-livewind::breadcrumb-item></x-livewind::breadcrumb>');

    expect($html)->toContain('extra');
});
