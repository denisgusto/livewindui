<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders with default props', function () {
    $html = Blade::render('<x-livewindui::button>Salvar</x-livewindui::button>');

    expect($html)
        ->toContain('<button')
        ->toContain('Salvar')
        ->toContain('type="button"')
        ->toContain('bg-indigo-600');
});

it('applies variant classes', function (string $variant, string $expectedClass) {
    $html = Blade::render("<x-livewindui::button variant=\"{$variant}\">X</x-livewindui::button>");

    expect($html)->toContain($expectedClass);
})->with([
    'primary' => ['primary', 'bg-indigo-600'],
    'secondary' => ['secondary', 'bg-gray-100'],
    'danger' => ['danger', 'bg-red-600'],
    'outline' => ['outline', 'border-gray-300'],
    'ghost' => ['ghost', 'hover:bg-gray-100'],
]);

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
        ->toContain('bg-indigo-600')
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
