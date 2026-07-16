{{-- Checkbox: controle booleano/grupo com label clicavel, descricao, wire:model e erro automatico. Props: model, label, description, value. Uso: <x-livewind::checkbox model="accepted" label="Aceito" /> --}}

@php
    $errors = $errors ?? new Illuminate\Support\ViewErrorBag();
    $hasError = filled($model) && $errors->has($model);
    $baseId = $attributes->get('id') ?? 'livewind-checkbox-'.md5((string) ($model ?? $label ?? $value ?? 'field'));
    $descriptionId = "{$baseId}-description";
    $wireModelAttribute = filled($model) ? 'wire:model' : null;

    $checkboxAttributes = $attributes
        ->except(['wire:model'])
        ->class([
            'peer h-4 w-4 rounded border-border text-accent transition focus:ring-accent',
            'border-danger focus:ring-danger' => $hasError,
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
                <span class="block text-sm font-medium text-surface-foreground peer-checked:text-accent-content">{{ $label }}</span>
            @endif

            @if ($hasError)
                <span id="{{ $descriptionId }}" class="mt-1 block text-sm text-danger">{{ $errors->first($model) }}</span>
            @elseif ($description)
                <span id="{{ $descriptionId }}" class="mt-1 block text-sm text-muted-foreground">{{ $description }}</span>
            @endif
        </span>
    </label>
</div>
