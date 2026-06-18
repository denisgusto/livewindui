<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders with default props', function () {
    $html = Blade::render('<x-livewindui::button>Salvar</x-livewindui::button>');

    expect($html)
        ->toContain('<button')
        ->toContain('Salvar')
        ->toContain('type="button"')
        ->toContain('bg-accent');
});

it('applies variant classes', function (string $variant, string $expectedClass) {
    $html = Blade::render("<x-livewindui::button variant=\"{$variant}\">X</x-livewindui::button>");

    expect($html)->toContain($expectedClass);
})->with([
    'primary' => ['primary', 'bg-accent'],
    'filled' => ['filled', 'bg-gray-100'],
    'secondary' => ['secondary', 'bg-gray-100'],
    'danger' => ['danger', 'bg-red-600'],
    'outline' => ['outline', 'border-gray-300'],
    'ghost' => ['ghost', 'hover:bg-gray-100'],
    'subtle' => ['subtle', 'hover:text-gray-700'],
]);

it('applies a literal color to the primary variant', function (string $color, string $expectedClass) {
    $html = Blade::render("<x-livewindui::button color=\"{$color}\">X</x-livewindui::button>");

    expect($html)->toContain($expectedClass);
})->with([
    'red' => ['red', 'bg-red-600'],
    'green' => ['green', 'bg-green-600'],
    'blue' => ['blue', 'bg-blue-600'],
    'amber' => ['amber', 'bg-amber-400'],
]);

it('includes dark mode classes on neutral variants', function () {
    $html = Blade::render('<x-livewindui::button variant="outline">X</x-livewindui::button>');

    expect($html)->toContain('dark:bg-gray-900');
});

it('renders as an anchor when href is provided', function () {
    $html = Blade::render('<x-livewindui::button href="/go">Ir</x-livewindui::button>');

    expect($html)
        ->toContain('<a')
        ->toContain('href="/go"')
        ->not->toContain('type="button"');
});

it('applies size classes', function (string $size, string $expectedClass) {
    $html = Blade::render("<x-livewindui::button size=\"{$size}\">X</x-livewindui::button>");

    expect($html)->toContain($expectedClass);
})->with([
    'sm' => ['sm', 'px-3'],
    'md' => ['md', 'py-2'],
    'lg' => ['lg', 'text-base'],
]);

it('merges consumer classes and arbitrary attributes', function () {
    $html = Blade::render('<x-livewindui::button class="w-full" id="save-button" data-test="button">Salvar</x-livewindui::button>');

    expect($html)
        ->toContain('w-full')
        ->toContain('bg-accent')
        ->toContain('id="save-button"')
        ->toContain('data-test="button"');
});

it('renders livewire loading and confirm attributes', function () {
    $html = Blade::render('<x-livewindui::button wire:click="save" loading="save" confirm="Tem certeza?">Salvar</x-livewindui::button>');

    expect($html)
        ->toContain('wire:click="save"')
        ->toContain('wire:confirm="Tem certeza?"')
        ->toContain('wire:loading')
        ->toContain('wire:target="save"')
        ->toContain('aria-busy="true"');
});
