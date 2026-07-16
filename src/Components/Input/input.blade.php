{{-- Input: campo de texto com label, hint, slots laterais, wire:model, mask (x-mask) e erro automatico. Props: model, modelLive, label, hint, type, mask; slots prefix/suffix. Uso: <x-livewind::input model="phone" mask="(99) 99999-9999" /> --}}
@props([])

<div>
    @if ($label)
        <label for="{{ $id() }}" class="mb-1 block text-sm font-medium text-surface-foreground">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        @isset($prefix)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-muted-foreground">
                {{ $prefix }}
            </div>
        @endisset

        <input {{ $inputAttributes(isset($prefix), isset($suffix)) }} />

        @isset($suffix)
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-muted-foreground">
                {{ $suffix }}
            </div>
        @endisset
    </div>

    @if ($hasError())
        <p id="{{ $descriptionId() }}" class="mt-1 text-sm text-danger">
            {{ $errorMessage() }}
        </p>
    @elseif ($hint)
        <p id="{{ $descriptionId() }}" class="mt-1 text-sm text-muted-foreground">
            {{ $hint }}
        </p>
    @endif
</div>
