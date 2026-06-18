<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tag prefix
    |--------------------------------------------------------------------------
    |
    | The namespace used by the package's anonymous Blade components, e.g.
    | <x-livewindui::button /> and <x-livewindui::modal />.
    |
    */
    'prefix' => 'livewindui',

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    |
    | The default accent ("brand") color used by primary buttons and other
    | interactive accents. This must be a Tailwind color name available in the
    | build. The actual color values are driven by CSS variables in
    | resources/css/livewindui.css (publish them to re-theme globally), but
    | components also accept a per-instance `color` prop that overrides this.
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
    | Default max width for <x-livewindui::modal />: sm, md, lg, xl or 2xl.
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
    | Default Tailwind classes for the first Blade components. Consumers can
    | publish the config and adjust these classes to match their design system.
    |
    */
    'button' => [
        'variants' => [
            'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 focus-visible:outline-indigo-600',
            'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200 focus-visible:outline-gray-400',
            'danger' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:outline-red-600',
            'outline' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus-visible:outline-gray-400',
            'ghost' => 'text-gray-700 hover:bg-gray-100 focus-visible:outline-gray-400',
        ],

        'sizes' => [
            'sm' => 'px-3 py-1.5 text-sm',
            'md' => 'px-4 py-2 text-sm',
            'lg' => 'px-5 py-2.5 text-base',
        ],
    ],
];
