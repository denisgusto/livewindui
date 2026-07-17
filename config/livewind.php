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
];
