{{-- Breadcrumb: navegacao hierarquica acessivel. Uso: <x-livewind::breadcrumb><x-livewind::breadcrumb-item href="/">Home</x-livewind::breadcrumb-item></x-livewind::breadcrumb> --}}
<nav {{ $attributes->class(['text-sm']) }} aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-2 text-surface-foreground">
        {{ $slot }}
    </ol>
</nav>
