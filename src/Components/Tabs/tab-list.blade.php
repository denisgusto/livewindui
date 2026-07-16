{{-- TabList: lista ARIA de botoes de abas. Uso: <x-livewind::tab-list><x-livewind::tab name="a">A</x-livewind::tab></x-livewind::tab-list> --}}
<div {{ $attributes->class(['flex gap-1 border-b border-border']) }} role="tablist">
    {{ $slot }}
</div>
