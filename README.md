# LiveWindUI

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777bb4.svg)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-10%2B-ff2d20.svg)](https://laravel.com/)
[![Livewire](https://img.shields.io/badge/Livewire-3.5%2B-fb70a9.svg)](https://livewire.laravel.com/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Tests](https://img.shields.io/badge/tests-passing-brightgreen.svg)](#development)

Blade components for Laravel Livewire, styled with Tailwind CSS and designed for zero custom JavaScript in the package.

## Quick Install

```bash
composer require denisgusto/livewindui
php artisan vendor:publish --tag=livewindui-config
php artisan vendor:publish --tag=livewindui-views
```

Add the package views to your Tailwind content paths:

```js
export default {
  content: [
    './resources/**/*.blade.php',
    './vendor/denisgusto/livewindui/resources/views/**/*.blade.php',
  ],
}
```

## Quick Example

Manual form markup:

```blade
<input wire:model="email" class="block w-full rounded-md border px-3 py-2">
@error('email')
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
```

LiveWindUI:

```blade
<x-livewindui::input model="email" label="E-mail" />
<x-livewindui::button type="submit" loading="save">Save</x-livewindui::button>
```

Integrated table:

```blade
<x-livewindui::data-table :columns="$columns" :rows="$contacts">
    <x-slot:header>
        <x-livewindui::button wire:click="openCreate">New contact</x-livewindui::button>
    </x-slot:header>
</x-livewindui::data-table>
```

## Components

- Buttons: `button`, `icon-button`
- Forms: `input`, `select`, `textarea`, `checkbox`, `radio`, `toggle`
- Feedback: `alert`, `toast`, `toast-item`
- Overlay and navigation: `modal`, `dropdown`, `tabs`, `breadcrumb`
- Data: `pagination`, `table`, `data-table`
- Layout: `container`, `card`, `divider`, `badge`, `spinner`

Every component is consumed with the configurable prefix:

```blade
<x-livewindui::button>Save</x-livewindui::button>
```

## Philosophy

- **Zero package JavaScript files:** behavior is expressed through Livewire and inline Alpine directives.
- **Tailwind-only styling:** no CSS framework, no DaisyUI, no custom theme runtime.
- **DX-first Blade API:** common Livewire patterns like `wire:model`, loading states, validation errors, modals and toasts are available with concise props.
- **Composable primitives:** complex screens can be built from small Blade components without losing access to Tailwind classes or normal Laravel views.

## Requirements

- PHP 8.1+
- Laravel 10, 11, 12 or 13
- Livewire 3.5+ or 4.3+
- Tailwind CSS 3+

## Publishing

```bash
php artisan vendor:publish --tag=livewindui-config
php artisan vendor:publish --tag=livewindui-views
```

## Development

```bash
composer install
vendor/bin/pint --test
vendor/bin/pest
```

The demo app lives in `demo/` and consumes the package through a Composer path repository.

## Contributing

Keep components Blade-first, accessible, Tailwind-only and compatible with Laravel 10+. Add focused Pest tests for every component change.

## License

LiveWindUI is open-sourced software licensed under the [MIT license](LICENSE).
