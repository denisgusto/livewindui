<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders the calendar wired to the lwCalendar bundle component', function () {
    $html = Blade::render('<x-livewind::calendar model="date" value="2024-01-15" />');

    expect($html)
        ->toContain('x-data="lwCalendar(')
        ->toContain('2024-01-15')
        ->toContain('x-text="monthLabel"')
        ->toContain('wire:model="date"');
});

it('omits the hidden input when no model is bound', function () {
    $html = Blade::render('<x-livewind::calendar />');

    expect($html)
        ->toContain('x-data="lwCalendar(')
        ->not->toContain('wire:model');
});
