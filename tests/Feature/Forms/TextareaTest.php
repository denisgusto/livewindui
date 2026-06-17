<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

it('renders with label hint and wire model', function () {
    $html = Blade::render('<x-livewindui::textarea model="notes" label="Observações" hint="Opcional" />');

    expect($html)
        ->toContain('<textarea')
        ->toContain('Observações')
        ->toContain('Opcional')
        ->toContain('wire:model="notes"')
        ->toContain('rows="4"');
});

it('renders max length counter', function () {
    $html = Blade::render('<x-livewindui::textarea model="notes" :max-length="140" />');

    expect($html)
        ->toContain('maxlength="140"')
        ->toContain('x-data="{ value: \'\' }"')
        ->toContain('x-text="value.length"')
        ->toContain('/140');
});

it('renders auto resize behavior', function () {
    $html = Blade::render('<x-livewindui::textarea model="notes" auto-resize />');

    expect($html)
        ->toContain('x-ref="textarea"')
        ->toContain('$event.target.style.height = &#039;auto&#039;')
        ->toContain('scrollHeight');
});

it('merges consumer classes and attributes', function () {
    $html = Blade::render('<x-livewindui::textarea model="notes" class="min-h-32" data-test="textarea" />');

    expect($html)
        ->toContain('min-h-32')
        ->toContain('border-gray-300')
        ->toContain('data-test="textarea"');
});

it('shows validation errors', function () {
    $errors = new ViewErrorBag;
    $errors->put('default', new MessageBag(['notes' => ['Observações são obrigatórias.']]));
    app('view')->share('errors', $errors);

    $html = Blade::render('<x-livewindui::textarea model="notes" label="Observações" />');

    expect($html)
        ->toContain('Observações são obrigatórias.')
        ->toContain('border-red-500')
        ->toContain('aria-invalid="true"');
});
