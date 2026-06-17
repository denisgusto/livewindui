{{-- Input: campo de texto com label, hint, slots laterais, wire:model e erro automatico. Props: model, modelLive, label, hint, prefix/suffix slots. Uso: <x-livewindui::input model="email" label="E-mail" /> --}}
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
    $baseId = $attributes->get('id') ?? 'livewindui-input-'.md5((string) ($model ?? $label ?? 'field'));
    $descriptionId = "{$baseId}-description";
    $wireModelAttribute = filled($model) ? ($modelLive ? 'wire:model.live' : 'wire:model') : null;

    $inputAttributes = $attributes
        ->except(['wire:model', 'wire:model.live'])
        ->class([
            'block w-full rounded-md border px-3 py-2 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:outline-none focus:ring-2 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500',
            'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' => ! $hasError,
            'border-red-500 focus:border-red-500 focus:ring-red-500' => $hasError,
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
        <label for="{{ $baseId }}" class="mb-1 block text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        @isset($prefix)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-500">
                {{ $prefix }}
            </div>
        @endisset

        <input {{ $inputAttributes }} />

        @isset($suffix)
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-gray-500">
                {{ $suffix }}
            </div>
        @endisset
    </div>

    @if ($hasError)
        <p id="{{ $descriptionId }}" class="mt-1 text-sm text-red-600">
            {{ $errors->first($model) }}
        </p>
    @elseif ($hint)
        <p id="{{ $descriptionId }}" class="mt-1 text-sm text-gray-500">
            {{ $hint }}
        </p>
    @endif
</div>
