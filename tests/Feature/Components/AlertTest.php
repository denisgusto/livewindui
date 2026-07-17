<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders with default props', function () {
    $html = Blade::render('<x-livewind::alert>Mensagem</x-livewind::alert>');

    expect($html)
        ->toContain('role="alert"')
        ->toContain('aria-live="polite"')
        ->toContain('Mensagem')
        ->toContain('bg-info/10');
});

it('applies variant classes', function (string $variant, string $expectedClass, string $ariaLive) {
    $html = Blade::render("<x-livewind::alert variant=\"{$variant}\">X</x-livewind::alert>");

    expect($html)
        ->toContain($expectedClass)
        ->toContain("aria-live=\"{$ariaLive}\"");
})->with([
    'success' => ['success', 'bg-success/10', 'polite'],
    'info' => ['info', 'bg-info/10', 'polite'],
    'warning' => ['warning', 'bg-warning/10', 'polite'],
    'danger' => ['danger', 'bg-danger/10', 'assertive'],
]);

it('renders title and body', function () {
    $html = Blade::render('<x-livewind::alert title="Salvo">Mensagem</x-livewind::alert>');

    expect($html)
        ->toContain('Salvo')
        ->toContain('Mensagem')
        ->toContain('font-medium');
});

it('renders dismissible behavior', function () {
    $html = Blade::render('<x-livewind::alert dismissible>Mensagem</x-livewind::alert>');

    expect($html)
        ->toContain('x-data="{ show: true }"')
        ->toContain('x-show="show"')
        ->toContain('x-on:click="show = false"')
        ->toContain('aria-label="Close alert"');
});

it('renders auto dismiss behavior', function () {
    $html = Blade::render('<x-livewind::alert :auto-dismiss="3000">Mensagem</x-livewind::alert>');

    expect($html)
        ->toContain('x-init="setTimeout(() => show = false, 3000)"')
        ->toContain('x-transition');
});

it('merges consumer classes and attributes', function () {
    $html = Blade::render('<x-livewind::alert class="mt-4" data-test="alert">Mensagem</x-livewind::alert>');

    expect($html)
        ->toContain('mt-4')
        ->toContain('bg-info/10')
        ->toContain('data-test="alert"');
});
