{{-- Button: botao com variantes (Flux-style), cor de destaque tematizavel, dark mode, loading e confirmacao. Props: variant, color, size, icon, icon-trailing, loading, confirm, href, square, type. --}}
@props([
    'variant' => 'primary',
    'color' => null,
    'size' => 'md',
    'icon' => null,
    'iconTrailing' => null,
    'loading' => null,
    'confirm' => null,
    'href' => null,
    'square' => false,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 rounded-md font-medium shadow-sm transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:pointer-events-none disabled:opacity-50';

    // Solid fill: themeable accent token by default, or a literal Tailwind color when `color` is set.
    $solidClasses = $color === null
        ? 'bg-accent text-accent-foreground hover:bg-accent-content focus-visible:outline-accent'
        : match ($color) {
            'zinc', 'gray', 'slate', 'neutral', 'stone' => 'bg-gray-700 text-white hover:bg-gray-800 focus-visible:outline-gray-700 dark:bg-gray-600 dark:hover:bg-gray-500',
            'red' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:outline-red-600 dark:bg-red-500 dark:hover:bg-red-400',
            'orange' => 'bg-orange-600 text-white hover:bg-orange-700 focus-visible:outline-orange-600 dark:bg-orange-500 dark:hover:bg-orange-400',
            'amber' => 'bg-amber-400 text-gray-900 hover:bg-amber-500 focus-visible:outline-amber-500 dark:bg-amber-500 dark:hover:bg-amber-400',
            'yellow' => 'bg-yellow-400 text-gray-900 hover:bg-yellow-500 focus-visible:outline-yellow-500 dark:bg-yellow-500 dark:hover:bg-yellow-400',
            'lime' => 'bg-lime-400 text-gray-900 hover:bg-lime-500 focus-visible:outline-lime-500 dark:bg-lime-500 dark:hover:bg-lime-400',
            'green' => 'bg-green-600 text-white hover:bg-green-700 focus-visible:outline-green-600 dark:bg-green-500 dark:hover:bg-green-400',
            'emerald' => 'bg-emerald-600 text-white hover:bg-emerald-700 focus-visible:outline-emerald-600 dark:bg-emerald-500 dark:hover:bg-emerald-400',
            'teal' => 'bg-teal-600 text-white hover:bg-teal-700 focus-visible:outline-teal-600 dark:bg-teal-500 dark:hover:bg-teal-400',
            'cyan' => 'bg-cyan-600 text-white hover:bg-cyan-700 focus-visible:outline-cyan-600 dark:bg-cyan-500 dark:hover:bg-cyan-400',
            'sky' => 'bg-sky-600 text-white hover:bg-sky-700 focus-visible:outline-sky-600 dark:bg-sky-500 dark:hover:bg-sky-400',
            'blue' => 'bg-blue-600 text-white hover:bg-blue-700 focus-visible:outline-blue-600 dark:bg-blue-500 dark:hover:bg-blue-400',
            'indigo' => 'bg-indigo-600 text-white hover:bg-indigo-700 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400',
            'violet' => 'bg-violet-600 text-white hover:bg-violet-700 focus-visible:outline-violet-600 dark:bg-violet-500 dark:hover:bg-violet-400',
            'purple' => 'bg-purple-600 text-white hover:bg-purple-700 focus-visible:outline-purple-600 dark:bg-purple-500 dark:hover:bg-purple-400',
            'fuchsia' => 'bg-fuchsia-600 text-white hover:bg-fuchsia-700 focus-visible:outline-fuchsia-600 dark:bg-fuchsia-500 dark:hover:bg-fuchsia-400',
            'pink' => 'bg-pink-600 text-white hover:bg-pink-700 focus-visible:outline-pink-600 dark:bg-pink-500 dark:hover:bg-pink-400',
            'rose' => 'bg-rose-600 text-white hover:bg-rose-700 focus-visible:outline-rose-600 dark:bg-rose-500 dark:hover:bg-rose-400',
            default => 'bg-accent text-accent-foreground hover:bg-accent-content focus-visible:outline-accent',
        };

    $variantClasses = match ($variant) {
        'primary' => $solidClasses,
        'filled', 'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200 focus-visible:outline-gray-400 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:outline-red-600 dark:bg-red-500 dark:hover:bg-red-400',
        'outline' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus-visible:outline-gray-400 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800',
        'ghost' => 'text-gray-700 shadow-none hover:bg-gray-100 focus-visible:outline-gray-400 dark:text-gray-200 dark:hover:bg-gray-800',
        'subtle' => 'text-gray-500 shadow-none hover:bg-gray-100 hover:text-gray-700 focus-visible:outline-gray-400 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200',
        default => $solidClasses,
    };

    $sizeClasses = $square
        ? match ($size) {
            'xs' => 'h-7 w-7 text-xs',
            'sm' => 'h-8 w-8 text-sm',
            'lg' => 'h-12 w-12 text-base',
            default => 'h-10 w-10 text-sm',
        }
        : match ($size) {
            'xs' => 'px-2.5 py-1 text-xs',
            'sm' => 'px-3 py-1.5 text-sm',
            'lg' => 'px-5 py-2.5 text-base',
            default => 'px-4 py-2 text-sm',
        };

    $tag = $href ? 'a' : 'button';

    $buttonAttributes = $attributes
        ->class([$baseClasses, $variantClasses, $sizeClasses])
        ->merge([
            'type' => $href ? null : $type,
            'href' => $href,
            'wire:confirm' => $confirm,
            'aria-busy' => $loading ? 'true' : null,
        ]);
@endphp

<{{ $tag }} {{ $buttonAttributes }}>
    @if ($icon)
        <span aria-hidden="true">{{ $icon }}</span>
    @endif

    @if ($loading)
        <span wire:loading wire:target="{{ $loading }}" class="contents">
            <x-livewindui::spinner size="sm" />
        </span>
        <span wire:loading.remove wire:target="{{ $loading }}" class="contents">
            {{ $slot }}
        </span>
    @else
        {{ $slot }}
    @endif

    @if ($iconTrailing)
        <span aria-hidden="true">{{ $iconTrailing }}</span>
    @endif
</{{ $tag }}>
