{{-- Breadcrumb: navegacao hierarquica acessivel. Uso: <x-livewindui::breadcrumb><x-livewindui::breadcrumb-item href="/">Home</x-livewindui::breadcrumb-item></x-livewindui::breadcrumb> --}}
<nav {{ $attributes->class(['text-sm']) }} aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-2 text-gray-600">
        {{ $slot }}
    </ol>
</nav>
