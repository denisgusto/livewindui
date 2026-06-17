@props([
    'model' => null,
    'label' => null,
    'description' => null,
    'size' => 'md',
])

@php
    $trackClasses = match ($size) {
        'sm' => 'h-5 w-9',
        'md' => 'h-6 w-11',
        'lg' => 'h-7 w-14',
        default => 'h-6 w-11',
    };

    $thumbClasses = match ($size) {
        'sm' => 'h-4 w-4 translate-x-0.5 peer-aria-checked:translate-x-4',
        'md' => 'h-5 w-5 translate-x-0.5 peer-aria-checked:translate-x-5',
        'lg' => 'h-6 w-6 translate-x-0.5 peer-aria-checked:translate-x-7',
        default => 'h-5 w-5 translate-x-0.5 peer-aria-checked:translate-x-5',
    };

    $entangledState = filled($model)
        ? '$wire.entangle('.Illuminate\Support\Js::from($model).')'
        : 'false';
@endphp

<div
    x-data="{ checked: {{ $entangledState }} }"
    {{ $attributes->class(['flex items-start justify-between gap-4']) }}
>
    <div class="min-w-0">
        @if ($label)
            <span class="block text-sm font-medium text-gray-900">{{ $label }}</span>
        @endif

        @if ($description)
            <span class="mt-1 block text-sm text-gray-500">{{ $description }}</span>
        @endif
    </div>

    <button
        type="button"
        role="switch"
        class="peer relative inline-flex shrink-0 rounded-full bg-gray-200 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 aria-checked:bg-indigo-600 {{ $trackClasses }}"
        x-on:click="checked = ! checked"
        x-on:keydown.enter.prevent="checked = ! checked"
        x-on:keydown.space.prevent="checked = ! checked"
        x-bind:aria-checked="checked.toString()"
    >
        <span class="pointer-events-none inline-block rounded-full bg-white shadow ring-0 transition {{ $thumbClasses }}"></span>
    </button>
</div>
