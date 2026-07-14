{{-- TableHeader: cabecalho de tabela com estado sortable opcional. Props: sortable, sorted, direction. Uso: <x-livewind::table-header sortable>Nome</x-livewind::table-header> --}}
@props([
    'sortable' => false,
    'sorted' => false,
    'direction' => 'asc',
])

<th
    scope="col"
    role="columnheader"
    {{ $attributes->class([
        'px-4 py-3 text-left text-xs font-semibold uppercase tracking-normal text-surface-foreground',
        'select-none' => $sortable,
    ]) }}
>
    @if ($sortable)
        <button type="button" class="inline-flex items-center gap-1 rounded-sm focus:outline-none focus:ring-2 focus:ring-accent">
            <span>{{ $slot }}</span>
            <span aria-hidden="true" class="text-muted-foreground">
                @if ($sorted)
                    {{ $direction === 'asc' ? '↑' : '↓' }}
                @else
                    ↕
                @endif
            </span>
        </button>
    @else
        {{ $slot }}
    @endif
</th>
