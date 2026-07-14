{{-- TableRow: linha de tabela para head/body. Props: selected. Uso: <x-livewind::table-row>...</x-livewind::table-row> --}}
@props([
    'selected' => false,
])

<tr {{ $attributes->class([
    'transition',
    'bg-accent/10' => $selected,
]) }}>
    {{ $slot }}
</tr>
