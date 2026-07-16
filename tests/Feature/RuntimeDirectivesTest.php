<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders the toast container via @livewindScripts', function () {
    $html = Blade::render('@livewindScripts');

    expect($html)
        ->toContain('x-data="lwToast(')
        ->toContain('x-for="toast in toasts"');
});

it('injects the served JS bundle via @livewindScripts', function () {
    $html = Blade::render('@livewindScripts');

    expect($html)
        ->toContain('<script src=')
        ->toContain('/livewind/livewind.js');
});

it('serves the js bundle over the asset route', function () {
    $response = $this->get('/livewind/livewind.js');

    $response->assertOk();
    expect($response->headers->get('Content-Type'))->toContain('javascript');
});

it('renders the dark-mode appearance script via @livewindAppearance', function () {
    $html = Blade::render('@livewindAppearance');

    expect($html)
        ->toContain('<script>')
        ->toContain("classList.toggle('dark'")
        ->toContain('window.Livewind.appearance')
        ->toContain('prefers-color-scheme: dark');
});
