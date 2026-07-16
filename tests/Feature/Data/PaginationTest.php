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

    $html = Blade::render('<x-livewind::pagination :paginator="$paginator" />', [
        'paginator' => $paginator,
    ]);

    expect($html)
        ->toContain('role="navigation"')
        ->toContain('aria-label="Pagination"')
        ->toContain('Showing')
        ->toContain('1</span>-<span')
        ->toContain('30</span> results')
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

    $html = Blade::render('<x-livewind::pagination :paginator="$paginator" compact />', [
        'paginator' => $paginator,
    ]);

    expect($html)
        ->toContain('Page 2 of 3')
        ->toContain('Previous')
        ->toContain('Next');
});
