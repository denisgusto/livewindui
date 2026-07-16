{{-- Toggle: switch acessivel com role=switch e estado Alpine/Livewire. Props: model, label, description, size. Uso: <x-livewind::toggle model="active" label="Ativo" /> --}}

@php
    $trackClasses = match ($size) {
        'sm' => 'h-5 w-9',
        'md' => 'h-6 w-11',
        'lg' => 'h-7 w-14',
        default => 'h-6 w-11',
    };

    $thumbClasses = match ($size) {
        'sm' => 'h-4 w-4',
        'md' => 'h-5 w-5',
        'lg' => 'h-6 w-6',
        default => 'h-5 w-5',
    };

    // Deslocamento do thumb quando ligado. Fica em x-bind:class reagindo ao
    // estado `checked` porque o thumb é filho do botão (não um irmão do peer),
    // então variantes `peer-*` do Tailwind nao se aplicam a ele.
    $thumbTranslate = match ($size) {
        'sm' => 'translate-x-4',
        'lg' => 'translate-x-7',
        default => 'translate-x-5',
    };

    $entangledState = filled($model)
        ? '$wire.entangle('.Illuminate\Support\Js::from($model).')'
        : 'false';
@endphp

<div
    x-data="{ checked: {{ $entangledState }} }"
    {{ $attributes->class(['flex items-start justify-between gap-4']) }}
>
    <div class="min-w-0">
        @if ($label)
            <span class="block text-sm font-medium text-surface-foreground">{{ $label }}</span>
        @endif

        @if ($description)
            <span class="mt-1 block text-sm text-muted-foreground">{{ $description }}</span>
        @endif
    </div>

    <button
        type="button"
        role="switch"
        class="peer relative inline-flex shrink-0 rounded-full bg-muted transition focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 aria-checked:bg-accent {{ $trackClasses }}"
        x-on:click="checked = ! checked"
        x-on:keydown.enter.prevent="checked = ! checked"
        x-on:keydown.space.prevent="checked = ! checked"
        x-bind:aria-checked="checked.toString()"
    >
        <span
            class="pointer-events-none inline-block rounded-full bg-surface shadow ring-0 transition {{ $thumbClasses }}"
            x-bind:class="checked ? '{{ $thumbTranslate }}' : 'translate-x-0.5'"
        ></span>
    </button>
</div>
