<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders breadcrumb navigation', function () {
    $html = Blade::render(<<<'BLADE'
        <x-livewindui::breadcrumb>
            <x-livewindui::breadcrumb-item href="/">Home</x-livewindui::breadcrumb-item>
            <x-livewindui::breadcrumb-item current>Atual</x-livewindui::breadcrumb-item>
        </x-livewindui::breadcrumb>
    BLADE);

    expect($html)
        ->toContain('aria-label="Breadcrumb"')
        ->toContain('href="/"')
        ->toContain('aria-current="page"')
        ->toContain('Home')
        ->toContain('Atual');
});

it('merges breadcrumb attributes', function () {
    $html = Blade::render('<x-livewindui::breadcrumb class="extra"><x-livewindui::breadcrumb-item current>X</x-livewindui::breadcrumb-item></x-livewindui::breadcrumb>');

    expect($html)->toContain('extra');
});
