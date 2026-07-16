{{-- Radio: opcao unica com label clicavel, descricao, wire:model e erro automatico. Props: model, label, description, value. Uso: <x-livewind::radio model="plan" value="pro" label="Pro" /> --}}

@php
    $errors = $errors ?? new Illuminate\Support\ViewErrorBag();
    $hasError = filled($model) && $errors->has($model);
    $baseId = $attributes->get('id') ?? 'livewind-radio-'.md5((string) ($model ?? $label ?? $value ?? 'field'));
    $descriptionId = "{$baseId}-description";
    $wireModelAttribute = filled($model) ? 'wire:model' : null;

    $radioAttributes = $attributes
        ->except(['wire:model'])
        ->class([
            'peer h-4 w-4 border-border text-accent transition focus:ring-accent',
            'border-danger focus:ring-danger' => $hasError,
        ])
        ->merge([
            'id' => $baseId,
            'type' => 'radio',
            'value' => $value,
            'aria-invalid' => $hasError ? 'true' : 'false',
            'aria-describedby' => ($hasError || $description) ? $descriptionId : null,
        ]);

    if ($wireModelAttribute) {
        $radioAttributes = $radioAttributes->merge([$wireModelAttribute => $model]);
    }
@endphp

<div>
    <label for="{{ $baseId }}" class="flex cursor-pointer items-start gap-3">
        <input {{ $radioAttributes }} />

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
