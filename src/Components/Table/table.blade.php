{{-- Table: tabela base com slots head e linhas/celulas auxiliares. Props: striped, hover, compact. Uso: <x-livewind::table><x-slot:head>...</x-slot:head>...</x-livewind::table> --}}

@php
    $tableClasses = 'min-w-full divide-y divide-border text-sm';
    $bodyClasses = trim(implode(' ', array_filter([
        'divide-y divide-border bg-surface',
        $striped ? '[&_tr:nth-child(even)]:bg-muted' : null,
        $hover ? '[&_tr:hover]:bg-accent/5' : null,
        $compact ? '[&_td]:py-2 [&_th]:py-2' : null,
    ])));
@endphp

<div {{ $attributes->class(['overflow-hidden rounded-lg border border-border bg-surface shadow-sm']) }}>
    <div class="overflow-x-auto">
        <table class="{{ $tableClasses }}">
            @isset($head)
                <thead class="bg-muted text-left text-xs font-semibold uppercase tracking-normal text-surface-foreground">
                    {{ $head }}
                </thead>
            @endisset

            <tbody class="{{ $bodyClasses }}">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
