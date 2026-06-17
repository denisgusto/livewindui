{{-- Table: tabela base com slots head e linhas/celulas auxiliares. Props: striped, hover, compact. Uso: <x-livewindui::table><x-slot:head>...</x-slot:head>...</x-livewindui::table> --}}
@props([
    'striped' => false,
    'hover' => true,
    'compact' => false,
])

@php
    $tableClasses = 'min-w-full divide-y divide-gray-200 text-sm';
    $bodyClasses = trim(implode(' ', array_filter([
        'divide-y divide-gray-100 bg-white',
        $striped ? '[&_tr:nth-child(even)]:bg-gray-50' : null,
        $hover ? '[&_tr:hover]:bg-indigo-50/40' : null,
        $compact ? '[&_td]:py-2 [&_th]:py-2' : null,
    ])));
@endphp

<div {{ $attributes->class(['overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm']) }}>
    <div class="overflow-x-auto">
        <table class="{{ $tableClasses }}">
            @isset($head)
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-normal text-gray-600">
                    {{ $head }}
                </thead>
            @endisset

            <tbody class="{{ $bodyClasses }}">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
