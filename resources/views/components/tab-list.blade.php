{{-- TabList: lista ARIA de botoes de abas. Uso: <x-livewindui::tab-list><x-livewindui::tab name="a">A</x-livewindui::tab></x-livewindui::tab-list> --}}
<div {{ $attributes->class(['flex gap-1 border-b border-gray-200 dark:border-gray-700']) }} role="tablist">
    {{ $slot }}
</div>
