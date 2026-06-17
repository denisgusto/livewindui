{{-- Select: select nativo com options por array/objeto, slot customizado, wire:model e erro automatico. Props: model, modelLive, label, hint, placeholder, options. Uso: <x-livewindui::select model="category" :options="$options" /> --}}
@props([
    'model' => null,
    'modelLive' => false,
    'label' => null,
    'hint' => null,
    'placeholder' => 'Selecione...',
    'options' => [],
])

@php
    $errors = $errors ?? new Illuminate\Support\ViewErrorBag();
    $hasError = filled($model) && $errors->has($model);
    $baseId = $attributes->get('id') ?? 'livewindui-select-'.md5((string) ($model ?? $label ?? 'field'));
    $descriptionId = "{$baseId}-description";
    $wireModelAttribute = filled($model) ? ($modelLive ? 'wire:model.live' : 'wire:model') : null;

    $selectAttributes = $attributes
        ->except(['wire:model', 'wire:model.live'])
        ->class([
            'block w-full rounded-md border px-3 py-2 text-sm text-gray-900 shadow-sm transition focus:outline-none focus:ring-2 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500',
            'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' => ! $hasError,
            'border-red-500 focus:border-red-500 focus:ring-red-500' => $hasError,
        ])
        ->merge([
            'id' => $baseId,
            'aria-invalid' => $hasError ? 'true' : 'false',
            'aria-describedby' => ($hasError || $hint) ? $descriptionId : null,
        ]);

    if ($wireModelAttribute) {
        $selectAttributes = $selectAttributes->merge([$wireModelAttribute => $model]);
    }
@endphp

<div>
    @if ($label)
        <label for="{{ $baseId }}" class="mb-1 block text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    @endif

    <select {{ $selectAttributes }}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @forelse ($options as $value => $option)
            @php
                $optionValue = is_array($option) ? ($option['value'] ?? $value) : (is_object($option) ? ($option->value ?? $value) : $value);
                $optionLabel = is_array($option) ? ($option['label'] ?? $optionValue) : (is_object($option) ? ($option->label ?? $optionValue) : $option);
            @endphp

            <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
        @empty
            {{ $slot }}
        @endforelse
    </select>

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
