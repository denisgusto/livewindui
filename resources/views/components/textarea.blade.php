{{-- Textarea: area de texto com label, hint, contador, auto-resize, wire:model e erro automatico. Props: model, modelLive, label, hint, rows, maxLength, autoResize. Uso: <x-livewindui::textarea model="notes" :max-length="240" /> --}}
@props([
    'model' => null,
    'modelLive' => false,
    'label' => null,
    'hint' => null,
    'rows' => 4,
    'maxLength' => null,
    'autoResize' => false,
])

@php
    $errors = $errors ?? new Illuminate\Support\ViewErrorBag();
    $hasError = filled($model) && $errors->has($model);
    $baseId = $attributes->get('id') ?? 'livewindui-textarea-'.md5((string) ($model ?? $label ?? 'field'));
    $descriptionId = "{$baseId}-description";
    $counterId = "{$baseId}-counter";
    $wireModelAttribute = filled($model) ? ($modelLive ? 'wire:model.live' : 'wire:model') : null;
    $hasAlpineState = filled($maxLength) || $autoResize;
    $inputHandler = trim(implode('; ', array_filter([
        filled($maxLength) ? 'value = $event.target.value' : null,
        $autoResize ? '$event.target.style.height = \'auto\'; $event.target.style.height = `${$event.target.scrollHeight}px`' : null,
    ])));

    $textareaAttributes = $attributes
        ->except(['wire:model', 'wire:model.live'])
        ->class([
            'block w-full rounded-md border px-3 py-2 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:outline-none focus:ring-2 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 dark:bg-gray-900 dark:text-gray-100 dark:placeholder:text-gray-500 dark:disabled:bg-gray-800',
            'border-gray-300 focus:border-accent focus:ring-accent dark:border-gray-600' => ! $hasError,
            'border-red-500 focus:border-red-500 focus:ring-red-500' => $hasError,
        ])
        ->merge([
            'id' => $baseId,
            'rows' => $rows,
            'maxlength' => $maxLength,
            'aria-invalid' => $hasError ? 'true' : 'false',
            'aria-describedby' => trim(($hasError || $hint ? $descriptionId : '').' '.(filled($maxLength) ? $counterId : '')) ?: null,
            'x-ref' => $hasAlpineState ? 'textarea' : null,
            'x-on:input' => $hasAlpineState ? $inputHandler : null,
        ]);

    if ($wireModelAttribute) {
        $textareaAttributes = $textareaAttributes->merge([$wireModelAttribute => $model]);
    }
@endphp

<div @if ($hasAlpineState) x-data="{ value: '' }" x-init="value = $refs.textarea.value" @endif>
    @if ($label)
        <label for="{{ $baseId }}" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $label }}
        </label>
    @endif

    <textarea {{ $textareaAttributes }}>{{ $slot }}</textarea>

    <div class="mt-1 flex items-start justify-between gap-3">
        <div>
            @if ($hasError)
                <p id="{{ $descriptionId }}" class="text-sm text-red-600 dark:text-red-400">
                    {{ $errors->first($model) }}
                </p>
            @elseif ($hint)
                <p id="{{ $descriptionId }}" class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $hint }}
                </p>
            @endif
        </div>

        @if ($maxLength)
            <p id="{{ $counterId }}" class="text-xs text-gray-500 dark:text-gray-400">
                <span x-text="value.length"></span>/{{ $maxLength }}
            </p>
        @endif
    </div>
</div>
