{{-- Checkbox: controle booleano/grupo com label clicavel, descricao, wire:model e erro automatico. Props: model, label, description, value. Uso: <x-livewindui::checkbox model="accepted" label="Aceito" /> --}}
@props([
    'model' => null,
    'label' => null,
    'description' => null,
    'value' => null,
])

@php
    $errors = $errors ?? new Illuminate\Support\ViewErrorBag();
    $hasError = filled($model) && $errors->has($model);
    $baseId = $attributes->get('id') ?? 'livewindui-checkbox-'.md5((string) ($model ?? $label ?? $value ?? 'field'));
    $descriptionId = "{$baseId}-description";
    $wireModelAttribute = filled($model) ? 'wire:model' : null;

    $checkboxAttributes = $attributes
        ->except(['wire:model'])
        ->class([
            'peer h-4 w-4 rounded border-gray-300 text-indigo-600 transition focus:ring-indigo-500',
            'border-red-500 focus:ring-red-500' => $hasError,
        ])
        ->merge([
            'id' => $baseId,
            'type' => 'checkbox',
            'value' => $value,
            'aria-invalid' => $hasError ? 'true' : 'false',
            'aria-describedby' => ($hasError || $description) ? $descriptionId : null,
        ]);

    if ($wireModelAttribute) {
        $checkboxAttributes = $checkboxAttributes->merge([$wireModelAttribute => $model]);
    }
@endphp

<div>
    <label for="{{ $baseId }}" class="flex cursor-pointer items-start gap-3">
        <input {{ $checkboxAttributes }} />

        <span>
            @if ($label)
                <span class="block text-sm font-medium text-gray-900 peer-checked:text-indigo-700">{{ $label }}</span>
            @endif

            @if ($hasError)
                <span id="{{ $descriptionId }}" class="mt-1 block text-sm text-red-600">{{ $errors->first($model) }}</span>
            @elseif ($description)
                <span id="{{ $descriptionId }}" class="mt-1 block text-sm text-gray-500">{{ $description }}</span>
            @endif
        </span>
    </label>
</div>
