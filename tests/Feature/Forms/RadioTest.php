<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

it('renders with label description and wire model', function () {
    $html = Blade::render('<x-livewindui::radio model="plan" value="pro" label="Pro" description="Plano avançado" />');

    expect($html)
        ->toContain('type="radio"')
        ->toContain('wire:model="plan"')
        ->toContain('value="pro"')
        ->toContain('Pro')
        ->toContain('Plano avançado');
});

it('merges consumer classes and attributes', function () {
    $html = Blade::render('<x-livewindui::radio model="plan" value="free" class="border-indigo-500" data-test="radio" />');

    expect($html)
        ->toContain('border-indigo-500')
        ->toContain('data-test="radio"');
});

it('shows validation errors', function () {
    $errors = new ViewErrorBag;
    $errors->put('default', new MessageBag(['plan' => ['Escolha um plano.']]));
    app('view')->share('errors', $errors);

    $html = Blade::render('<x-livewindui::radio model="plan" value="pro" label="Pro" />');

    expect($html)
        ->toContain('Escolha um plano.')
        ->toContain('border-red-500')
        ->toContain('aria-invalid="true"');
});
