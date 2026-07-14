<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders switch semantics and entangled model', function () {
    $html = Blade::render('<x-livewind::toggle model="active" label="Ativo" description="Disponível para uso" />');

    expect($html)
        ->toContain('role="switch"')
        ->toContain('x-bind:aria-checked="checked.toString()"')
        ->toContain('$wire.entangle(&#039;active&#039;)')
        ->toContain('Ativo')
        ->toContain('Disponível para uso');
});

it('applies size classes', function (string $size, string $expectedClass) {
    $html = Blade::render("<x-livewind::toggle size=\"{$size}\" />");

    expect($html)->toContain($expectedClass);
})->with([
    'sm' => ['sm', 'h-5 w-9'],
    'md' => ['md', 'h-6 w-11'],
    'lg' => ['lg', 'h-7 w-14'],
]);

it('moves the thumb via checked state (not peer variants)', function () {
    $html = Blade::render('<x-livewind::toggle model="active" />');

    // O thumb é filho do botão, então precisa reagir ao estado `checked`
    // diretamente — variantes peer-* nao se aplicam a descendentes.
    expect($html)
        ->toContain("x-bind:class=\"checked ? 'translate-x-5' : 'translate-x-0.5'\"")
        ->not->toContain('peer-aria-checked:translate-x');
});

it('supports local state when model is omitted', function () {
    $html = Blade::render('<x-livewind::toggle label="Local" />');

    expect($html)
        ->toContain('x-data="{ checked: false }"')
        ->toContain('Local');
});
