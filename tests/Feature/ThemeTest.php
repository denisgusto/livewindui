<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('exposes the default accent in config', function () {
    expect(config('livewindui.theme.accent'))->toBe('indigo');
});

it('ships the theme css and no longer ships a tailwind js preset', function () {
    expect(file_exists(__DIR__.'/../../resources/css/livewindui.css'))->toBeTrue()
        ->and(file_exists(__DIR__.'/../../tailwind.preset.js'))->toBeFalse();
});

it('defines the semantic tokens via @theme inline for light and dark', function () {
    $css = file_get_contents(__DIR__.'/../../resources/css/livewindui.css');

    expect($css)
        ->toContain('@custom-variant dark')
        ->toContain('@theme inline')
        ->toContain(':root')
        ->toContain('.dark')
        ->toContain('--lw-accent')
        ->toContain('--lw-accent-foreground')
        ->toContain('--color-danger')
        ->toContain('--color-surface')
        ->toContain('--color-muted')
        ->toContain('--color-border');
});

it('uses semantic tokens in the migrated button (no dark: classes)', function () {
    $button = Blade::render('<x-livewindui::button>X</x-livewindui::button>');

    expect($button)
        ->toContain('bg-accent')
        ->toContain('text-accent-foreground')
        ->not->toContain('dark:');
});
