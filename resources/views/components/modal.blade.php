{{-- Modal: overlay acessivel com eventos globais, ESC, backdrop e trap focus. Props: name, maxWidth, closeable, show. Uso: <x-livewindui::modal name="confirm">...</x-livewindui::modal> --}}
@props([
    'name' => 'default',
    'maxWidth' => 'md',
    'closeable' => true,
    'show' => false,
])

@php
    $maxWidthClasses = match ($maxWidth) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        default => 'max-w-md',
    };

    $modalId = 'modal-title-'.preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $name);
@endphp

<div
    x-data="{
        show: @js($show),
        open() {
            this.show = true;
            document.body.classList.add('overflow-hidden');
        },
        close() {
            this.show = false;
            document.body.classList.remove('overflow-hidden');
        },
    }"
    x-on:livewindui-modal-open.window="if ($event.detail.name === '{{ $name }}') open()"
    x-on:livewindui-modal-close.window="if ($event.detail.name === '{{ $name }}') close()"
    @if ($closeable) x-on:keydown.escape.window="close()" @endif
    x-show="show"
    x-cloak
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $modalId }}"
>
    <div
        x-show="show"
        x-transition.opacity
        @if ($closeable) x-on:click="close()" @endif
        class="fixed inset-0 z-40 bg-black/50"
    ></div>

    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
        <div
            x-show="show"
            x-transition
            x-trap.noscroll="show"
            {{ $attributes->class([
                'w-full rounded-lg bg-white shadow-xl pointer-events-auto',
                $maxWidthClasses,
            ]) }}
        >
            @if ($closeable)
                <div class="flex justify-end px-4 pt-4">
                    <button
                        type="button"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-md text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        x-on:click="close()"
                        aria-label="Fechar modal"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>
</div>
