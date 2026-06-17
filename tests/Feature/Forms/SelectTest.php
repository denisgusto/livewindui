<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

it('renders associative options and wire model', function () {
    $html = Blade::render('<x-livewindui::select model="category" label="Categoria" :options="[\'a\' => \'Alpha\', \'b\' => \'Beta\']" />');

    expect($html)
        ->toContain('<select')
        ->toContain('Categoria')
        ->toContain('wire:model="category"')
        ->toContain('<option value="a">Alpha</option>')
        ->toContain('<option value="b">Beta</option>');
});

it('renders array object style options', function () {
    $html = Blade::render('<x-livewindui::select model="category" :options="[[\'value\' => \'a\', \'label\' => \'Alpha\']]" />');

    expect($html)->toContain('<option value="a">Alpha</option>');
});

it('renders custom option slot when options are empty', function () {
    $html = Blade::render('<x-livewindui::select model="category"><option value="x">Custom</option></x-livewindui::select>');

    expect($html)->toContain('<option value="x">Custom</option>');
});

it('merges consumer classes and attributes', function () {
    $html = Blade::render('<x-livewindui::select model="category" class="w-64" data-test="select" />');

    expect($html)
        ->toContain('w-64')
        ->toContain('border-gray-300')
        ->toContain('data-test="select"');
});

it('shows validation errors', function () {
    $errors = new ViewErrorBag;
    $errors->put('default', new MessageBag(['category' => ['Categoria obrigatória.']]));
    app('view')->share('errors', $errors);

    $html = Blade::render('<x-livewindui::select model="category" label="Categoria" />');

    expect($html)
        ->toContain('Categoria obrigatória.')
        ->toContain('border-red-500')
        ->toContain('aria-invalid="true"');
});
