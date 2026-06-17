{{-- Toast: container global de notificacoes por evento Livewire/browser. Props: position, duration. Uso: <x-livewindui::toast /> --}}
@props([
    'position' => 'top-right',
    'duration' => 4000,
])

@php
    $positionClasses = match ($position) {
        'top-left' => 'top-0 left-0 items-start',
        'bottom-right' => 'bottom-0 right-0 items-end',
        'bottom-left' => 'bottom-0 left-0 items-start',
        'top-center' => 'top-0 left-1/2 -translate-x-1/2 items-center',
        'top-right' => 'top-0 right-0 items-end',
        default => 'top-0 right-0 items-end',
    };
@endphp

<div
    x-data="{
        toasts: [],
        nextId: 1,
        add(payload) {
            const detail = Array.isArray(payload) ? (payload[0] ?? {}) : (payload ?? {});
            const toast = {
                id: this.nextId++,
                variant: detail.variant ?? 'info',
                title: detail.title ?? null,
                message: detail.message ?? '',
            };

            this.toasts.push(toast);
            setTimeout(() => this.remove(toast.id), {{ (int) $duration }});
        },
        remove(id) {
            this.toasts = this.toasts.filter((toast) => toast.id !== id);
        },
        roleFor(variant) {
            return ['warning', 'danger'].includes(variant) ? 'alert' : 'status';
        },
    }"
    x-on:livewire:init.window="Livewire.on('livewindui:toast.show', (event) => add(event))"
    x-on:livewindui:toast.show.window="add($event.detail)"
    {{ $attributes->class([
        'pointer-events-none fixed z-50 flex w-full flex-col gap-3 p-4 sm:w-auto',
        $positionClasses,
    ]) }}
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition
            class="pointer-events-auto w-full max-w-sm rounded-md border p-4 shadow-lg"
            x-bind:class="{
                'border-green-200 bg-green-50 text-green-800': toast.variant === 'success',
                'border-blue-200 bg-blue-50 text-blue-800': toast.variant === 'info',
                'border-yellow-200 bg-yellow-50 text-yellow-800': toast.variant === 'warning',
                'border-red-200 bg-red-50 text-red-800': toast.variant === 'danger',
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
