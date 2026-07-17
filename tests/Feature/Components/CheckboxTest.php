<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

it('renders with label description and wire model', function () {
    $html = Blade::render('<x-livewind::checkbox model="accepted" label="Aceito" description="Termos de uso" />');

    expect($html)
        ->toContain('type="checkbox"')
        ->toContain('wire:model="accepted"')
        ->toContain('Aceito')
        ->toContain('Termos de uso')
        ->toContain('<label');
});

it('renders value for grouped checkboxes', function () {
    $html = Blade::render('<x-livewind::checkbox model="roles" value="admin" label="Admin" />');

    expect($html)->toContain('value="admin"');
});

it('merges consumer classes and attributes', function () {
    $html = Blade::render('<x-livewind::checkbox model="accepted" class="rounded-sm" data-test="checkbox" />');

    expect($html)
        ->toContain('rounded-sm')
        ->toContain('data-test="checkbox"');
});

it('shows validation errors', function () {
    $errors = new ViewErrorBag;
    $errors->put('default', new MessageBag(['accepted' => ['Você precisa aceitar os termos.']]));
    app('view')->share('errors', $errors);

    $html = Blade::render('<x-livewind::checkbox model="accepted" label="Aceito" />');

    expect($html)
        ->toContain('Você precisa aceitar os termos.')
        ->toContain('border-danger')
        ->toContain('aria-invalid="true"');
});
