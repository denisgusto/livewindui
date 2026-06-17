{{-- TableRow: linha de tabela para head/body. Props: selected. Uso: <x-livewindui::table-row>...</x-livewindui::table-row> --}}
@props([
    'selected' => false,
])

<tr {{ $attributes->class([
    'transition',
    'bg-indigo-50' => $selected,
]) }}>
    {{ $slot }}
</tr>
