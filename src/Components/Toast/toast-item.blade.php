{{-- ToastItem: item estatico de toast com variante e role semantico. Props: variant, title, message, dismissible. Uso: <x-livewind::toast-item variant="info" message="OK" /> --}}

@php
    $variantClasses = match ($variant) {
        'success' => 'border-success/30 bg-success/10 text-success',
        'warning' => 'border-warning/30 bg-warning/10 text-warning',
        'danger' => 'border-danger/30 bg-danger/10 text-danger',
        'info' => 'border-info/30 bg-info/10 text-info',
        default => 'border-info/30 bg-info/10 text-info',
    };

    $role = in_array($variant, ['warning', 'danger'], true) ? 'alert' : 'status';
@endphp

<div
    @if ($dismissible) x-data="{ show: true }" x-show="show" x-transition @endif
    {{ $attributes->class([
        'pointer-events-auto w-full max-w-sm rounded-md border p-4 shadow-lg',
        $variantClasses,
    ])->merge([
        'role' => $role,
    ]) }}
>
    <div class="flex gap-3">
        <div class="min-w-0 flex-1">
            @if ($title)
                <p class="text-sm font-medium">{{ $title }}</p>
            @endif

            <p class="{{ $title ? 'mt-1 ' : '' }}text-sm">
                {{ $message ?? $slot }}
            </p>
        </div>

        @if ($dismissible)
            <button
                type="button"
                class="-m-1.5 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md opacity-70 transition hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-offset-2"
                x-on:click="show = false"
                aria-label="{{ __('livewind::ui.toast.close') }}"
            >
                <span aria-hidden="true">&times;</span>
            </button>
        @endif
    </div>
</div>
