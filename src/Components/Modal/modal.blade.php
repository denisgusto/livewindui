{{-- Modal: overlay acessivel com eventos globais, ESC, backdrop e trap focus. Props: name, maxWidth, closeable, show. Uso: <x-livewind::modal name="confirm">...</x-livewind::modal> --}}

@php
    $maxWidth ??= config('livewind.modal.max_width', 'md');

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
        trigger: null,
        open() {
            this.trigger = document.activeElement;
            this.show = true;
            document.body.classList.add('overflow-hidden');
            this.$nextTick(() => this.$refs.panel.focus());
        },
        close() {
            this.show = false;
            document.body.classList.remove('overflow-hidden');
            this.$nextTick(() => this.trigger?.focus());
        },
    }"
    x-init="if (show) { document.body.classList.add('overflow-hidden'); $nextTick(() => $refs.panel.focus()) }"
    x-on:livewind-modal-open.window="if ($event.detail.name === @js($name)) open()"
    x-on:livewind-modal-close.window="if (! $event.detail.name || $event.detail.name === @js($name)) close()"
    @if ($closeable) x-on:keydown.escape.window="close()" @endif
    x-show="show"
    x-cloak
    role="dialog"
    aria-modal="true"
    @if ($title) aria-labelledby="{{ $modalId }}" @else aria-label="{{ str_replace(['-', '_'], ' ', ucfirst((string) $name)) }}" @endif
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
            x-ref="panel"
            tabindex="-1"
            {{ $attributes->class([
                'w-full rounded-lg bg-surface text-surface-foreground shadow-xl pointer-events-auto outline-none',
                $maxWidthClasses,
            ]) }}
        >
            @if ($title)
                <h2 id="{{ $modalId }}" class="sr-only">{{ $title }}</h2>
            @endif
            @if ($closeable)
                <div class="flex justify-end px-4 pt-4">
                    <button
                        type="button"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-md text-muted-foreground transition hover:bg-muted hover:text-surface-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                        x-on:click="close()"
                        aria-label="{{ __('livewind::ui.modal.close') }}"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>
</div>
