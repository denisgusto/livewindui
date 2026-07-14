<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tag prefix
    |--------------------------------------------------------------------------
    |
    | The namespace used by the package's anonymous Blade components, e.g.
    | <x-livewind::button /> and <x-livewind::modal />.
    |
    */
    'prefix' => 'livewind',

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    |
    | The default accent ("brand") color used by primary buttons and other
    | interactive accents. This is informational only: the actual color values
    | are driven by the semantic CSS variables in resources/css/livewind.css
    | (publish them to re-theme globally). Component color is fully semantic —
    | there is no per-instance literal `color` prop.
    |
    */
    'theme' => [
        'accent' => 'indigo',
    ],

    /*
    |--------------------------------------------------------------------------
    | Toast
    |--------------------------------------------------------------------------
    |
    | Default behavior for the global toast container. `position` is one of
    | top-right, top-left, top-center, bottom-right, bottom-left. `duration` is
    | in milliseconds (0 = permanent). `max` limits how many toasts stay stacked.
    |
    */
    'toast' => [
        'position' => 'top-right',
        'duration' => 4000,
        'max' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Modal
    |--------------------------------------------------------------------------
    |
    | Default max width for <x-livewind::modal />: sm, md, lg, xl or 2xl.
    |
    */
    'modal' => [
        'max_width' => 'md',
    ],

    /*
    |--------------------------------------------------------------------------
    | Button
    |--------------------------------------------------------------------------
    |
    | Global defaults for buttons. Visual customization is driven by the
    | semantic tokens in resources/css/livewind.css.
    |
    */
    'button' => [
        'variant' => 'primary',
        'size' => 'md',
    ],
];
