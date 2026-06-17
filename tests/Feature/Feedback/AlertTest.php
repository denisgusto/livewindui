<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders with default props', function () {
    $html = Blade::render('<x-livewindui::alert>Mensagem</x-livewindui::alert>');

    expect($html)
        ->toContain('role="alert"')
        ->toContain('aria-live="polite"')
        ->toContain('Mensagem')
        ->toContain('bg-blue-50');
});

it('applies variant classes', function (string $variant, string $expectedClass, string $ariaLive) {
    $html = Blade::render("<x-livewindui::alert variant=\"{$variant}\">X</x-livewindui::alert>");

    expect($html)
        ->toContain($expectedClass)
        ->toContain("aria-live=\"{$ariaLive}\"");
})->with([
    'success' => ['success', 'bg-green-50', 'polite'],
    'info' => ['info', 'bg-blue-50', 'polite'],
    'warning' => ['warning', 'bg-yellow-50', 'polite'],
    'danger' => ['danger', 'bg-red-50', 'assertive'],
]);

it('renders title and body', function () {
    $html = Blade::render('<x-livewindui::alert title="Salvo">Mensagem</x-livewindui::alert>');

    expect($html)
        ->toContain('Salvo')
        ->toContain('Mensagem')
        ->toContain('font-medium');
});

it('renders dismissible behavior', function () {
    $html = Blade::render('<x-livewindui::alert dismissible>Mensagem</x-livewindui::alert>');

    expect($html)
        ->toContain('x-data="{ show: true }"')
        ->toContain('x-show="show"')
        ->toContain('x-on:click="show = false"')
        ->toContain('aria-label="Fechar alerta"');
});

it('renders auto dismiss behavior', function () {
    $html = Blade::render('<x-livewindui::alert :auto-dismiss="3000">Mensagem</x-livewindui::alert>');

    expect($html)
        ->toContain('x-init="setTimeout(() => show = false, 3000)"')
        ->toContain('x-transition');
});

it('merges consumer classes and attributes', function () {
    $html = Blade::render('<x-livewindui::alert class="mt-4" data-test="alert">Mensagem</x-livewindui::alert>');

    expect($html)
        ->toContain('mt-4')
        ->toContain('bg-blue-50')
        ->toContain('data-test="alert"');
});
