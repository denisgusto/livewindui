<?php

declare(strict_types=1);

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;

it('renders a length aware paginator', function () {
    $paginator = new LengthAwarePaginator(
        items: range(1, 10),
        total: 30,
        perPage: 10,
        currentPage: 1,
    );

    $html = Blade::render('<x-livewindui::pagination :paginator="$paginator" />', [
        'paginator' => $paginator,
    ]);

    expect($html)
        ->toContain('role="navigation"')
        ->toContain('aria-label="Paginacao"')
        ->toContain('Mostrando')
        ->toContain('1</span>-<span')
        ->toContain('30</span> resultados')
        ->toContain('wire:click="previousPage')
        ->toContain('disabled');
});

it('renders compact pagination controls', function () {
    $paginator = new LengthAwarePaginator(
        items: range(11, 20),
        total: 30,
        perPage: 10,
        currentPage: 2,
    );

    $html = Blade::render('<x-livewindui::pagination :paginator="$paginator" compact />', [
        'paginator' => $paginator,
    ]);

    expect($html)
        ->toContain('Pagina 2 de 3')
        ->toContain('Anterior')
        ->toContain('Proxima');
});
