{{-- Pagination: controles reativos para paginator Livewire/Laravel. Props: paginator, compact. Uso: <x-livewindui::pagination :paginator="$rows" /> --}}
@props([
    'paginator' => null,
    'compact' => false,
])

@php
    $hasPaginator = $paginator instanceof Illuminate\Contracts\Pagination\Paginator;
    $pageName = $hasPaginator && method_exists($paginator, 'getPageName') ? $paginator->getPageName() : 'page';
    $currentPage = $hasPaginator ? $paginator->currentPage() : 1;
    $lastPage = $hasPaginator && method_exists($paginator, 'lastPage') ? $paginator->lastPage() : $currentPage;
    $onFirstPage = $hasPaginator ? $paginator->onFirstPage() : true;
    $hasMorePages = $hasPaginator ? $paginator->hasMorePages() : false;
    $total = $hasPaginator && method_exists($paginator, 'total') ? $paginator->total() : ($hasPaginator ? $paginator->count() : 0);
    $firstItem = $hasPaginator && method_exists($paginator, 'firstItem') ? $paginator->firstItem() : null;
    $lastItem = $hasPaginator && method_exists($paginator, 'lastItem') ? $paginator->lastItem() : null;
    $pages = $lastPage > 1 ? range(1, $lastPage) : [1];
@endphp

@if ($hasPaginator)
    <nav
        role="navigation"
        aria-label="Paginacao"
        {{ $attributes->class(['flex flex-col gap-3 border-t border-gray-200 px-4 py-3 sm:flex-row sm:items-center sm:justify-between']) }}
    >
        <p class="text-sm text-gray-600">
            @if ($firstItem && $lastItem)
                Mostrando <span class="font-medium text-gray-900">{{ $firstItem }}</span>-<span class="font-medium text-gray-900">{{ $lastItem }}</span> de <span class="font-medium text-gray-900">{{ $total }}</span> resultados
            @else
                {{ $total }} resultados
            @endif
        </p>

        <div class="flex items-center gap-2">
            <button
                type="button"
                class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:opacity-50"
                wire:click="previousPage('{{ $pageName }}')"
                @disabled($onFirstPage)
                aria-label="Pagina anterior"
            >
                Anterior
            </button>

            @if (! $compact)
                <div class="hidden items-center gap-1 sm:flex">
                    @foreach ($pages as $page)
                        <button
                            type="button"
                            class="h-9 min-w-9 rounded-md px-3 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $page === $currentPage ? 'bg-indigo-600 text-white' : 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50' }}"
                            wire:click="gotoPage({{ $page }}, '{{ $pageName }}')"
                            aria-current="{{ $page === $currentPage ? 'page' : 'false' }}"
                        >
                            {{ $page }}
                        </button>
                    @endforeach
                </div>
            @else
                <span class="px-2 text-sm text-gray-600">
                    Pagina {{ $currentPage }} de {{ $lastPage }}
                </span>
            @endif

            <button
                type="button"
                class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:opacity-50"
                wire:click="nextPage('{{ $pageName }}')"
                @disabled(! $hasMorePages)
                aria-label="Proxima pagina"
            >
                Proxima
            </button>
        </div>
    </nav>
@endif
