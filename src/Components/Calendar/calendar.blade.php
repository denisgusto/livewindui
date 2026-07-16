{{-- Calendar: date picker de data unica. Requer o bundle (@livewindScripts). Props: model, value. Uso: <x-livewind::calendar model="date" /> --}}
@props([])

<div
    x-data="lwCalendar({ value: @js($value), locale: @js(str_replace('_', '-', app()->getLocale())) })"
    {{ $attributes->class(['inline-block rounded-lg border border-border bg-surface p-3 text-surface-foreground shadow-sm']) }}
>
    <div class="mb-2 flex items-center justify-between gap-4">
        <button
            type="button"
            x-on:click="prevMonth()"
            aria-label="{{ __('livewind::ui.previous_month') }}"
            class="inline-flex h-8 w-8 items-center justify-center rounded-md text-muted-foreground transition hover:bg-muted focus:outline-none focus:ring-2 focus:ring-accent"
        >&lsaquo;</button>

        <span class="text-sm font-medium capitalize" x-text="monthLabel"></span>

        <button
            type="button"
            x-on:click="nextMonth()"
            aria-label="{{ __('livewind::ui.next_month') }}"
            class="inline-flex h-8 w-8 items-center justify-center rounded-md text-muted-foreground transition hover:bg-muted focus:outline-none focus:ring-2 focus:ring-accent"
        >&rsaquo;</button>
    </div>

    <div class="mb-1 grid grid-cols-7 gap-1 text-center text-xs text-muted-foreground">
        <template x-for="weekday in weekdays" :key="weekday">
            <span x-text="weekday"></span>
        </template>
    </div>

    <div class="grid grid-cols-7 gap-1">
        <template x-for="(day, index) in days" :key="index">
            <button
                type="button"
                x-on:click="select(day)"
                x-text="day"
                x-bind:disabled="! day"
                x-bind:class="! day
                    ? 'invisible'
                    : (isSelected(day)
                        ? 'bg-accent text-accent-foreground'
                        : 'text-surface-foreground hover:bg-muted')"
                class="h-8 w-8 rounded-md text-sm transition focus:outline-none focus:ring-2 focus:ring-accent"
            ></button>
        </template>
    </div>

    @if ($model)
        <input type="hidden" x-ref="input" id="{{ $id() }}" wire:model="{{ $model }}" />
    @endif
</div>
