{{-- Input: campo de texto com label, hint, slots laterais, wire:model e erro automatico. Props: model, modelLive, label, hint, prefix/suffix slots. Uso: <x-livewind::input model="email" label="E-mail" /> --}}
@props([
    'model' => null,
    'modelLive' => false,
    'label' => null,
    'hint' => null,
    'type' => 'text',
])

@php
    $errors = $errors ?? new Illuminate\Support\ViewErrorBag();
    $hasError = filled($model) && $errors->has($model);
    $baseId = $attributes->get('id') ?? 'livewind-input-'.md5((string) ($model ?? $label ?? 'field'));
    $descriptionId = "{$baseId}-description";
    $wireModelAttribute = filled($model) ? ($modelLive ? 'wire:model.live' : 'wire:model') : null;

    $inputAttributes = $attributes
        ->except(['wire:model', 'wire:model.live'])
        ->class([
            'block w-full rounded-md border px-3 py-2 text-sm text-surface-foreground shadow-sm transition placeholder:text-muted-foreground focus:outline-none focus:ring-2 disabled:cursor-not-allowed disabled:bg-muted disabled:text-muted-foreground',
            'border-border focus:border-accent focus:ring-accent' => ! $hasError,
            'border-danger focus:border-danger focus:ring-danger' => $hasError,
            'pl-10' => isset($prefix),
            'pr-10' => isset($suffix),
        ])
        ->merge([
            'id' => $baseId,
            'type' => $type,
            'aria-invalid' => $hasError ? 'true' : 'false',
            'aria-describedby' => ($hasError || $hint) ? $descriptionId : null,
        ]);

    if ($wireModelAttribute) {
        $inputAttributes = $inputAttributes->merge([$wireModelAttribute => $model]);
    }
@endphp

<div>
    @if ($label)
        <label for="{{ $baseId }}" class="mb-1 block text-sm font-medium text-surface-foreground">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        @isset($prefix)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-muted-foreground">
                {{ $prefix }}
            </div>
        @endisset

        <input {{ $inputAttributes }} />

        @isset($suffix)
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-muted-foreground">
                {{ $suffix }}
            </div>
        @endisset
    </div>

    @if ($hasError)
        <p id="{{ $descriptionId }}" class="mt-1 text-sm text-danger">
            {{ $errors->first($model) }}
        </p>
    @elseif ($hint)
        <p id="{{ $descriptionId }}" class="mt-1 text-sm text-muted-foreground">
            {{ $hint }}
        </p>
    @endif
</div>
