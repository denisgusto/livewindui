<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('exposes the default accent in config', function () {
    expect(config('livewindui.theme.accent'))->toBe('indigo');
});

it('ships the theme css and tailwind preset', function () {
    expect(file_exists(__DIR__.'/../../resources/css/livewindui.css'))->toBeTrue()
        ->and(file_exists(__DIR__.'/../../tailwind.preset.js'))->toBeTrue();
});

it('defines the accent variables for light and dark in the theme css', function () {
    $css = file_get_contents(__DIR__.'/../../resources/css/livewindui.css');

    expect($css)
        ->toContain(':root')
        ->toContain('.dark')
        ->toContain('--lw-accent')
        ->toContain('--lw-accent-foreground');
});

it('uses the accent token and dark variants in components', function () {
    $button = Blade::render('<x-livewindui::button>X</x-livewindui::button>');
    $card = Blade::render('<x-livewindui::card>X</x-livewindui::card>');

    expect($button)->toContain('bg-accent')
        ->and($card)->toContain('dark:bg-gray-900');
});
