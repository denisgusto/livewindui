{{-- Alert: feedback inline com variantes, titulo, dismiss e auto-dismiss. Props: variant, title, dismissible, autoDismiss. Uso: <x-livewind::alert variant="success" title="Salvo">OK</x-livewind::alert> --}}

@php
    $variantClasses = match ($variant) {
        'success' => 'border-success/30 bg-success/10 text-success',
        'warning' => 'border-warning/30 bg-warning/10 text-warning',
        'danger' => 'border-danger/30 bg-danger/10 text-danger',
        'info' => 'border-info/30 bg-info/10 text-info',
        default => 'border-info/30 bg-info/10 text-info',
    };

    $titleClasses = match ($variant) {
        'success' => 'text-success',
        'warning' => 'text-warning',
        'danger' => 'text-danger',
        'info' => 'text-info',
        default => 'text-info',
    };

    $bodyClasses = match ($variant) {
        'success' => 'text-success',
        'warning' => 'text-warning',
        'danger' => 'text-danger',
        'info' => 'text-info',
        default => 'text-info',
    };

    $isInteractive = $dismissible || filled($autoDismiss);
    $alertAttributes = $attributes
        ->class(['rounded-md border p-4', $variantClasses])
        ->merge([
            'role' => 'alert',
            'aria-live' => $variant === 'danger' ? 'assertive' : 'polite',
        ]);
@endphp

<div
    @if ($isInteractive) x-data="{ show: true }" x-show="show" x-transition @endif
    @if ($autoDismiss) x-init="setTimeout(() => show = false, {{ (int) $autoDismiss }})" @endif
    {{ $alertAttributes }}
>
    <div class="flex gap-3">
        <div class="min-w-0 flex-1">
            @if ($title)
                <p class="text-sm font-medium {{ $titleClasses }}">{{ $title }}</p>
            @endif

            <div class="{{ $title ? 'mt-1 ' : '' }}text-sm {{ $bodyClasses }}">
                {{ $slot }}
            </div>
        </div>

        @if ($dismissible)
            <button
                type="button"
                class="-m-1.5 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md opacity-70 transition hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-offset-2"
                x-on:click="show = false"
                aria-label="{{ __('livewind::ui.alert.close') }}"
            >
                <span aria-hidden="true">&times;</span>
            </button>
        @endif
    </div>
</div>
