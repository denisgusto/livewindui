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
