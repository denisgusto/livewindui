<?php

declare(strict_types=1);

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;

it('uses english strings by default', function () {
    $html = Blade::render('<x-livewind::alert dismissible>Hi</x-livewind::alert>');

    expect($html)->toContain('Close alert');
});

it('switches component strings to pt_BR', function () {
    app()->setLocale('pt_BR');

    $html = Blade::render('<x-livewind::alert dismissible>Oi</x-livewind::alert>');

    expect($html)->toContain('Fechar alerta');
});

it('translates pagination controls', function () {
    app()->setLocale('pt_BR');

    $paginator = new LengthAwarePaginator(
        items: range(1, 5),
        total: 30,
        perPage: 10,
        currentPage: 1,
    );

    $html = Blade::render('<x-livewind::pagination :paginator="$paginator" />', [
        'paginator' => $paginator,
    ]);

    expect($html)
        ->toContain('Anterior')
        ->toContain('Próxima')
        ->toContain('Mostrando');
});
