@props([])

@php
    $tag         = $tag();
    $hasContent  = trim($slot) !== '';
    $isSquare    = $isSquare($hasContent);
    $showLoading = $shouldShowLoading($attributes);
    $loadingTarget = $loadingTarget($attributes);
    $confirm = $attributes->get('confirm');
    $buttonAttributes = $attributes->except('confirm');
    if ($confirm) {
        $buttonAttributes = $buttonAttributes->merge(['wire:confirm' => $confirm]);
    }
    $classes     = $classes() . ($isSquare ? ' aspect-square px-0 justify-center' : '');
@endphp

<{{ $tag }}
    @if ($tag === 'a' && $href) href="{{ $href }}" @endif
    @if ($tag === 'button') type="{{ $type }}" @endif
    @if ($tooltip) title="{{ $tooltip }}" @endif
    data-lw-button
    {{ $buttonAttributes->class($classes) }}
>
    {{-- Leading icon --}}
    @if ($icon)
        <span @if ($showLoading) wire:loading.remove @endif>
            <x-dynamic-component
                :component="'heroicon-m-' . $icon"
                :class="$iconSize() . ' shrink-0'"
            />
        </span>
    @endif

    {{-- Loading spinner replaces leading content during request --}}
    @if ($showLoading)
        <span wire:loading @if ($loadingTarget) wire:target="{{ $loadingTarget }}" @endif class="contents" aria-busy="true">
            <svg
                class="{{ $iconSize() }} shrink-0 animate-spin"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
            </svg>
        </span>
    @endif

    {{-- Text content --}}
    @if ($hasContent)
        <span @if ($showLoading) wire:loading.class="opacity-70" @endif>
            {{ $slot }}
        </span>
    @endif

    {{-- Trailing icon --}}
    @if ($iconTrailing)
        <x-dynamic-component
            :component="'heroicon-m-' . $iconTrailing"
            :class="$iconSize() . ' shrink-0'"
        />
    @endif

    {{-- Keyboard shortcut hint --}}
    @if ($kbd)
        <kbd class="ml-1 text-[0.65rem] opacity-60 font-mono tracking-tight">
            {{ $kbd }}
        </kbd>
    @endif
</{{ $tag }}>
