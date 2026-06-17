{{-- TableCell: celula de tabela com padding padrao. Props: compact. Uso: <x-livewindui::table-cell>Valor</x-livewindui::table-cell> --}}
@props([
    'compact' => false,
])

<td {{ $attributes->class([
    'px-4 text-sm text-gray-700',
    $compact ? 'py-2' : 'py-4',
]) }}>
    {{ $slot }}
</td>
