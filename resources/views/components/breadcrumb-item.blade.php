{{-- Breadcrumb item: item de breadcrumb com href opcional e current. Props: href, current. Uso: <x-livewindui::breadcrumb-item current>Atual</x-livewindui::breadcrumb-item> --}}
@props([
    'href' => null,
    'current' => false,
])

<li {{ $attributes->class(['flex items-center gap-2']) }}>
    @if ($href && ! $current)
        <a href="{{ $href }}" class="font-medium text-gray-600 transition hover:text-gray-950 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            {{ $slot }}
        </a>
    @else
        <span class="font-medium text-gray-950" @if ($current) aria-current="page" @endif>
            {{ $slot }}
        </span>
    @endif

    @if (! $current)
        <span aria-hidden="true" class="text-gray-300">/</span>
    @endif
</li>
