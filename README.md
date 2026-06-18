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

LiveWindUI targets **Tailwind CSS v4**, configured in CSS. In your `app.css`, import
Tailwind, import the theme, and register the package views as a source:

```css
@import "tailwindcss";
@import "../../vendor/denisgusto/livewindui/resources/css/livewindui.css";
@source "../../vendor/denisgusto/livewindui/resources/views";
```

No `tailwind.config.js` and no JS preset are needed.

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

## Toasts & Modals

LiveWindUI ships a global toast container and a named modal, both driven by events
with **zero package JavaScript files** (logic lives in inline Alpine).

### Container

Add the toast container once in your layout (it auto-stacks, auto-dismisses and
survives `wire:navigate` via `@persist`):

```blade
<x-livewindui::toast />
```

### The `Livewind` facade

Mirroring FluxUI's API, trigger toasts and modals from anywhere:

```php
use Livewind\Facades\Livewind;

Livewind::toast(message: 'Saved!', title: 'Done', variant: 'success', duration: 3000);
Livewind::success('Saved!');          // success | info | warning | danger | error
Livewind::error('Something failed');  // error -> danger variant

Livewind::modal('confirm-delete')->show();   // ->open() / ->close()
Livewind::modals()->close();                  // close every open modal
```

`duration` is in milliseconds; `0` keeps the toast until dismissed. The payload
also accepts FluxUI aliases (`heading`/`text`) besides `title`/`message`.

When called **outside** a Livewire request (e.g. a controller before a redirect),
toasts are flashed to the session and rendered on the next page load.

### From a Livewire component

Use the trait to dispatch from `$this`, or call the facade directly:

```php
use Livewind\Concerns\InteractsWithToasts;

class SaveContact extends Component
{
    use InteractsWithToasts;

    public function save(): void
    {
        $this->toast(message: 'Contact saved', variant: 'success');
    }
}
```

### From JavaScript / Alpine

```js
window.LiveWindUI.toast('Quick message');
window.LiveWindUI.toast({ variant: 'success', title: 'Done', message: 'Saved!' });
```

### Defaults

Publish the config to change defaults for `toast` (`position`, `duration`, `max`)
and `modal` (`max_width`):

```php
'toast' => ['position' => 'top-right', 'duration' => 4000, 'max' => 5],
'modal' => ['max_width' => 'md'],
```

## Theming & Dark Mode

LiveWindUI uses a **semantic token** color system (Tailwind v4 `@theme`), inspired by
shadcn/ui. Components reference role tokens — `accent`, `danger`, `success`, `warning`,
`surface`, `muted`, `border` (each with a `-foreground` pair) — that swap automatically
under the `.dark` class. Dark mode needs **no `dark:` classes** in your markup.

### Setup

Import Tailwind and the theme in your `app.css` (see Quick Install):

```css
@import "tailwindcss";
@import "../../vendor/denisgusto/livewindui/resources/css/livewindui.css";
@source "../../vendor/denisgusto/livewindui/resources/views";
```

Publish the theme to customize the raw values:

```bash
php artisan vendor:publish --tag=livewindui-theme
```

### Theming

Re-theme the **whole** library by overriding the raw role variables in your own
`app.css`, after the import. One change recolors every component:

```css
/* make the whole system green */
:root { --lw-accent: 34 197 94; --lw-accent-content: 22 163 74; }
.dark { --lw-accent: 74 222 128; --lw-accent-content: 134 239 172; }
```

Buttons expose Flux-style semantic `variant`s (`primary`, `filled`/`secondary`,
`outline`, `ghost`, `subtle`, `danger`) — all themeable, no literal colors:

```blade
<x-livewindui::button>Primary (accent)</x-livewindui::button>
<x-livewindui::button variant="subtle">Subtle</x-livewindui::button>
<x-livewindui::button variant="danger">Delete</x-livewindui::button>
```

### Dark mode

Toggle by adding/removing `.dark` on `<html>` — tokens do the rest.
A no-flash snippet for your layout `<head>`:

```html
<script>
  const m = localStorage.getItem('lw-appearance') || 'system';
  const dark = m === 'dark' || (m === 'system' && matchMedia('(prefers-color-scheme: dark)').matches);
  document.documentElement.classList.toggle('dark', dark);
</script>
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
