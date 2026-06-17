<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders switch semantics and entangled model', function () {
    $html = Blade::render('<x-livewindui::toggle model="active" label="Ativo" description="Disponível para uso" />');

    expect($html)
        ->toContain('role="switch"')
        ->toContain('x-bind:aria-checked="checked.toString()"')
        ->toContain('$wire.entangle(&#039;active&#039;)')
        ->toContain('Ativo')
        ->toContain('Disponível para uso');
});

it('applies size classes', function (string $size, string $expectedClass) {
    $html = Blade::render("<x-livewindui::toggle size=\"{$size}\" />");

    expect($html)->toContain($expectedClass);
})->with([
    'sm' => ['sm', 'h-5 w-9'],
    'md' => ['md', 'h-6 w-11'],
    'lg' => ['lg', 'h-7 w-14'],
]);

it('supports local state when model is omitted', function () {
    $html = Blade::render('<x-livewindui::toggle label="Local" />');

    expect($html)
        ->toContain('x-data="{ checked: false }"')
        ->toContain('Local');
});
