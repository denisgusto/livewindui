{{-- Toast: container global de notificacoes por evento Livewire/browser. Props: position, duration, max. Uso: <x-livewind::toast /> (ou via @livewindScripts). A logica vive no bundle (Alpine.data lwToast). --}}
@php
    $position ??= config('livewind.toast.position', 'top-right');
    $duration ??= config('livewind.toast.duration', 4000);
    $max ??= config('livewind.toast.max', 5);

    $positionClasses = match ($position) {
        'top-left' => 'top-0 left-0 items-start',
        'bottom-right' => 'bottom-0 right-0 items-end',
        'bottom-left' => 'bottom-0 left-0 items-start',
        'top-center' => 'top-0 left-1/2 -translate-x-1/2 items-center',
        'top-right' => 'top-0 right-0 items-end',
        default => 'top-0 right-0 items-end',
    };

    $config = [
        'duration' => (int) $duration,
        'max' => (int) $max,
        'flashed' => \Livewind\Facades\Livewind::flashedToasts(),
    ];
@endphp

@persist('livewind-toasts')
<div
    x-data="lwToast(@js($config))"
    {{ $attributes->class([
        'pointer-events-none fixed z-50 flex w-full flex-col gap-3 p-4 sm:w-auto',
        $positionClasses,
    ]) }}
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.visible"
            x-transition
            x-on:mouseenter="pause(toast)"
            x-on:mouseleave="resume(toast)"
            class="pointer-events-auto w-full max-w-sm rounded-md border p-4 shadow-lg"
            x-bind:class="{
                'border-success/30 bg-success/10 text-success': toast.variant === 'success',
                'border-info/30 bg-info/10 text-info': toast.variant === 'info',
                'border-warning/30 bg-warning/10 text-warning': toast.variant === 'warning',
                'border-danger/30 bg-danger/10 text-danger': toast.variant === 'danger',
            }"
            x-bind:role="roleFor(toast.variant)"
        >
            <div class="flex gap-3">
                <div class="min-w-0 flex-1">
                    <p x-show="toast.title" class="text-sm font-medium" x-text="toast.title"></p>
                    <p class="text-sm" x-bind:class="{ 'mt-1': toast.title }" x-text="toast.message"></p>
                </div>

                <button
                    type="button"
                    class="-m-1.5 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md opacity-70 transition hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-offset-2"
                    x-on:click="remove(toast.id)"
                    aria-label="{{ __('livewind::ui.toast.close') }}"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </template>
</div>
@endpersist
