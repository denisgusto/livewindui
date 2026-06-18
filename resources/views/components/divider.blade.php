{{-- Divider: separador horizontal com label opcional. Props: label. Uso: <x-livewindui::divider label="Ou" /> --}}
@props([
    'label' => null,
])

@if ($label)
    <div {{ $attributes->class(['relative']) }}>
        <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
        </div>
        <div class="relative flex justify-center">
            <span class="bg-white px-3 text-sm text-gray-500 dark:bg-gray-900 dark:text-gray-400">{{ $label }}</span>
        </div>
    </div>
@else
    <hr {{ $attributes->class(['border-gray-200 dark:border-gray-700']) }} />
@endif
