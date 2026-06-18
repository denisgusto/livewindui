{{-- Toast: container global de notificacoes por evento Livewire/browser. Props: position, duration, max. Uso: <x-livewindui::toast /> --}}
@props([
    'position' => null,
    'duration' => null,
    'max' => null,
])

@php
    $position ??= config('livewindui.toast.position', 'top-right');
    $duration ??= config('livewindui.toast.duration', 4000);
    $max ??= config('livewindui.toast.max', 5);

    $positionClasses = match ($position) {
        'top-left' => 'top-0 left-0 items-start',
        'bottom-right' => 'bottom-0 right-0 items-end',
        'bottom-left' => 'bottom-0 left-0 items-start',
        'top-center' => 'top-0 left-1/2 -translate-x-1/2 items-center',
        'top-right' => 'top-0 right-0 items-end',
        default => 'top-0 right-0 items-end',
    };

    $flashedToasts = \LiveWindUi\Facades\Livewind::flashedToasts();
@endphp

@persist('livewindui-toasts')
<div
    x-data="{
        toasts: [],
        nextId: 1,
        defaultDuration: {{ (int) $duration }},
        max: {{ (int) $max }},
        add(payload) {
            const raw = Array.isArray(payload) ? (payload[0] ?? {}) : (payload ?? {});
            const detail = typeof raw === 'string' ? { message: raw } : raw;
            const duration = detail.duration !== undefined && detail.duration !== null
                ? Number(detail.duration)
                : this.defaultDuration;

            const variant = detail.variant ?? 'info';
            const title = detail.title ?? detail.heading ?? null;
            const message = detail.message ?? detail.text ?? '';

            const duplicate = this.toasts.find((toast) =>
                toast.variant === variant && toast.title === title && toast.message === message
            );
            if (duplicate) {
                if (duplicate.timer) clearTimeout(duplicate.timer);
                duplicate.timer = null;
                duplicate.remaining = duplicate.duration;
                this.startTimer(duplicate);
                return;
            }

            const toast = {
                id: this.nextId++,
                variant: variant,
                title: title,
                message: message,
                duration: duration,
                remaining: duration,
                startedAt: null,
                timer: null,
                visible: false,
            };

            this.toasts.push(toast);
            if (this.max > 0) {
                while (this.toasts.length > this.max) {
                    this.remove(this.toasts[0].id);
                }
            }
            const added = this.toasts[this.toasts.length - 1];
            this.$nextTick(() => { added.visible = true; });
            this.startTimer(added);
        },
        startTimer(toast) {
            if (! toast.duration || toast.duration <= 0) return;
            toast.startedAt = Date.now();
            toast.timer = setTimeout(() => this.remove(toast.id), toast.remaining);
        },
        pause(toast) {
            if (! toast.timer) return;
            clearTimeout(toast.timer);
            toast.timer = null;
            toast.remaining -= Date.now() - toast.startedAt;
        },
        resume(toast) {
            if (toast.timer || ! toast.duration || toast.duration <= 0) return;
            this.startTimer(toast);
        },
        remove(id) {
            const toast = this.toasts.find((toast) => toast.id === id);
            if (toast && toast.timer) clearTimeout(toast.timer);
            this.toasts = this.toasts.filter((toast) => toast.id !== id);
        },
        roleFor(variant) {
            return ['warning', 'danger'].includes(variant) ? 'alert' : 'status';
        },
    }"
    x-init="
        window.addEventListener('livewindui:toast.show', (event) => add(event.detail));
        window.addEventListener('livewindui:toast', (event) => add(event.detail));
        window.LiveWindUI = window.LiveWindUI || {};
        window.LiveWindUI.toast = (payload) => window.dispatchEvent(
            new CustomEvent('livewindui:toast', { detail: typeof payload === 'string' ? { message: payload } : payload })
        );
        @js($flashedToasts).forEach((flashed) => add(flashed));
    "
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
                'border-green-200 bg-green-50 text-green-800 dark:border-green-900/50 dark:bg-green-950 dark:text-green-200': toast.variant === 'success',
                'border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-900/50 dark:bg-blue-950 dark:text-blue-200': toast.variant === 'info',
                'border-yellow-200 bg-yellow-50 text-yellow-800 dark:border-yellow-900/50 dark:bg-yellow-950 dark:text-yellow-200': toast.variant === 'warning',
                'border-red-200 bg-red-50 text-red-800 dark:border-red-900/50 dark:bg-red-950 dark:text-red-200': toast.variant === 'danger',
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
                    aria-label="Fechar notificacao"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </template>
</div>
@endpersist
