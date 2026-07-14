<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders with default props', function () {
    $html = Blade::render('<x-livewind::button>Salvar</x-livewind::button>');

    expect($html)
        ->toContain('<button')
        ->toContain('Salvar')
        ->toContain('type="button"')
        ->toContain('bg-accent');
});

it('renders a heroicon svg when the icon prop is set', function () {
    // Depende de blade-ui-kit/blade-heroicons (declarado no composer.json).
    $html = Blade::render('<x-livewind::button icon="pencil">Editar</x-livewind::button>');

    expect($html)
        ->toContain('<svg')
        ->toContain('Editar');
});

it('applies variant classes', function (string $variant, string $expectedClass) {
    $html = Blade::render("<x-livewind::button variant=\"{$variant}\">X</x-livewind::button>");

    expect($html)->toContain($expectedClass);
})->with([
    'primary' => ['primary', 'bg-accent'],
    'filled' => ['filled', 'bg-muted'],
    'secondary' => ['secondary', 'bg-muted'],
    'danger' => ['danger', 'bg-danger'],
    'outline' => ['outline', 'border-border'],
    'ghost' => ['ghost', 'hover:bg-muted'],
    'subtle' => ['subtle', 'text-muted-foreground'],
]);

it('uses semantic neutral tokens (no dark: classes)', function () {
    $html = Blade::render('<x-livewind::button variant="outline">X</x-livewind::button>');

    expect($html)
        ->toContain('bg-surface')
        ->toContain('border-border')
        ->not->toContain('dark:');
});

it('renders as an anchor when href is provided', function () {
    $html = Blade::render('<x-livewind::button href="/go">Ir</x-livewind::button>');

    expect($html)
        ->toContain('<a')
        ->toContain('href="/go"')
        ->not->toContain('type="button"');
});

it('applies size classes', function (string $size, string $expectedClass) {
    $html = Blade::render("<x-livewind::button size=\"{$size}\">X</x-livewind::button>");

    expect($html)->toContain($expectedClass);
})->with([
    'sm' => ['sm', 'px-3'],
    'md' => ['md', 'py-2'],
    'lg' => ['lg', 'text-base'],
]);

it('merges consumer classes and arbitrary attributes', function () {
    $html = Blade::render('<x-livewind::button class="w-full" id="save-button" data-test="button">Salvar</x-livewind::button>');

    expect($html)
        ->toContain('w-full')
        ->toContain('bg-accent')
        ->toContain('id="save-button"')
        ->toContain('data-test="button"');
});

it('renders livewire loading and confirm attributes', function () {
    $html = Blade::render('<x-livewind::button wire:click="save" loading="save" confirm="Tem certeza?">Salvar</x-livewind::button>');

    expect($html)
        ->toContain('wire:click="save"')
        ->toContain('wire:confirm="Tem certeza?"')
        ->toContain('wire:loading')
        ->toContain('wire:target="save"')
        ->toContain('aria-busy="true"');
});
