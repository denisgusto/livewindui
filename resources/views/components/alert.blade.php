@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => false,
    'autoDismiss' => null,
])

@php
    $variantClasses = match ($variant) {
        'success' => 'border-green-200 bg-green-50 text-green-800',
        'warning' => 'border-yellow-200 bg-yellow-50 text-yellow-800',
        'danger' => 'border-red-200 bg-red-50 text-red-800',
        'info' => 'border-blue-200 bg-blue-50 text-blue-800',
        default => 'border-blue-200 bg-blue-50 text-blue-800',
    };

    $titleClasses = match ($variant) {
        'success' => 'text-green-900',
        'warning' => 'text-yellow-900',
        'danger' => 'text-red-900',
        'info' => 'text-blue-900',
        default => 'text-blue-900',
    };

    $bodyClasses = match ($variant) {
        'success' => 'text-green-700',
        'warning' => 'text-yellow-700',
        'danger' => 'text-red-700',
        'info' => 'text-blue-700',
        default => 'text-blue-700',
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
                aria-label="Fechar alerta"
            >
                <span aria-hidden="true">&times;</span>
            </button>
        @endif
    </div>
</div>
