<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders the signature canvas wired to the lwSignature bundle component', function () {
    $html = Blade::render('<x-livewind::signature model="sign" />');

    expect($html)
        ->toContain('x-data="lwSignature(')
        ->toContain('x-ref="canvas"')
        ->toContain('touch-none')
        ->toContain('wire:model="sign"');
});

it('applies a custom pen color and height', function () {
    $html = Blade::render('<x-livewind::signature height="h-64" pen-color="#ff0000" />');

    expect($html)
        ->toContain('#ff0000')
        ->toContain('h-64');
});
