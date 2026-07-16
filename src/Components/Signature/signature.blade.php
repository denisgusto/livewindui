{{-- Signature: assinatura em canvas (pointer events), exporta PNG data URL. Requer o bundle (@livewindScripts). Props: model, height, penColor. Uso: <x-livewind::signature model="signature" /> --}}
@props([])

<div
    x-data="lwSignature({ penColor: @js($penColor) })"
    {{ $attributes->class(['relative']) }}
>
    <canvas
        x-ref="canvas"
        x-on:pointerdown="start($event)"
        x-on:pointermove="move($event)"
        x-on:pointerup.window="stop()"
        x-on:pointerleave="stop()"
        class="w-full touch-none rounded-md border border-border bg-surface {{ $height }}"
    ></canvas>

    <button
        type="button"
        x-on:click="clear()"
        class="mt-2 inline-flex items-center rounded-md border border-border bg-surface px-3 py-1.5 text-sm text-surface-foreground transition hover:bg-muted focus:outline-none focus:ring-2 focus:ring-accent"
    >
        {{ __('livewind::ui.clear') }}
    </button>

    @if ($model)
        <input type="hidden" x-ref="input" id="{{ $id() }}" wire:model="{{ $model }}" />
    @endif
</div>
