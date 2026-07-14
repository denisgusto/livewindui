{{-- Textarea: area de texto com label, hint, contador, auto-resize, wire:model e erro automatico. Props: model, modelLive, label, hint, rows, maxLength, autoResize. Uso: <x-livewind::textarea model="notes" :max-length="240" /> --}}
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
    $baseId = $attributes->get('id') ?? 'livewind-textarea-'.md5((string) ($model ?? $label ?? 'field'));
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
            'block w-full rounded-md border px-3 py-2 text-sm text-surface-foreground shadow-sm transition placeholder:text-muted-foreground focus:outline-none focus:ring-2 disabled:cursor-not-allowed disabled:bg-muted disabled:text-muted-foreground',
            'border-border focus:border-accent focus:ring-accent' => ! $hasError,
            'border-danger focus:border-danger focus:ring-danger' => $hasError,
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
        <label for="{{ $baseId }}" class="mb-1 block text-sm font-medium text-surface-foreground">
            {{ $label }}
        </label>
    @endif

    <textarea {{ $textareaAttributes }}>{{ $slot }}</textarea>

    <div class="mt-1 flex items-start justify-between gap-3">
        <div>
            @if ($hasError)
                <p id="{{ $descriptionId }}" class="text-sm text-danger">
                    {{ $errors->first($model) }}
                </p>
            @elseif ($hint)
                <p id="{{ $descriptionId }}" class="text-sm text-muted-foreground">
                    {{ $hint }}
                </p>
            @endif
        </div>

        @if ($maxLength)
            <p id="{{ $counterId }}" class="text-xs text-muted-foreground">
                <span x-text="value.length"></span>/{{ $maxLength }}
            </p>
        @endif
    </div>
</div>
