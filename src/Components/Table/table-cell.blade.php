{{-- TableCell: celula de tabela com padding padrao. Props: compact. Uso: <x-livewind::table-cell>Valor</x-livewind::table-cell> --}}

<td {{ $attributes->class([
    'px-4 text-sm text-surface-foreground',
    $compact ? 'py-2' : 'py-4',
]) }}>
    {{ $slot }}
</td>
