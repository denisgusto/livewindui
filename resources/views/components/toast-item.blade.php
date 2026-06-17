@props([
    'variant' => 'info',
    'title' => null,
    'message' => null,
    'dismissible' => true,
])

@php
    $variantClasses = match ($variant) {
        'success' => 'border-green-200 bg-green-50 text-green-800',
        'warning' => 'border-yellow-200 bg-yellow-50 text-yellow-800',
        'danger' => 'border-red-200 bg-red-50 text-red-800',
        'info' => 'border-blue-200 bg-blue-50 text-blue-800',
        default => 'border-blue-200 bg-blue-50 text-blue-800',
    };

    $role = in_array($variant, ['warning', 'danger'], true) ? 'alert' : 'status';
@endphp

<div
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
                aria-label="Fechar notificacao"
            >
                <span aria-hidden="true">&times;</span>
            </button>
        @endif
    </div>
</div>
