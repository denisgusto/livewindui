<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders divider without label', function () {
    $html = Blade::render('<x-livewindui::divider class="my-4" />');

    expect($html)
        ->toContain('<hr')
        ->toContain('border-gray-200')
        ->toContain('my-4');
});

it('renders divider with label', function () {
    $html = Blade::render('<x-livewindui::divider label="Ou" />');

    expect($html)
        ->toContain('Ou')
        ->toContain('border-t border-gray-200');
});
