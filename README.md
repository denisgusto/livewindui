# LiveWindUI

LiveWindUI is a Laravel package for reusable Livewire UI components styled with Tailwind CSS.

This repository is at the start of the package development cycle. The sprint plan lives in `docs/`, while the distributable package files stay at the project root.

## Requirements

- PHP 8.1+
- Laravel 10, 11, 12 or 13
- Livewire 3.5+ or 4.3+
- Tailwind CSS configured in the host application

## Installation

```bash
composer require denisgusto/livewindui
```

Laravel package discovery registers the service provider automatically.

## Tailwind

Add the package views to the host application's `tailwind.config.js` content list:

```js
export default {
    content: [
        './resources/**/*.blade.php',
        './vendor/denisgusto/livewindui/resources/views/**/*.blade.php',
    ],
}
```

## Publishing

```bash
php artisan vendor:publish --tag=livewindui-config
php artisan vendor:publish --tag=livewindui-views
```

## Usage

```blade
<x-livewindui::button>
    Save
</x-livewindui::button>

<x-livewindui::button variant="secondary" size="sm">
    Cancel
</x-livewindui::button>

<x-livewindui::input model="email" label="E-mail" hint="Use your best email." />

<x-livewindui::select
    model="category"
    label="Category"
    :options="['lead' => 'Lead', 'customer' => 'Customer']"
/>

<x-livewindui::textarea model="notes" label="Notes" :max-length="280" />

<x-livewindui::toggle model="active" label="Active" />

<x-livewindui::alert variant="success" title="Saved" dismissible>
    Your changes were saved.
</x-livewindui::alert>

<x-livewindui::toast />

<x-livewindui::modal name="confirm-delete" max-width="sm">
    <div class="p-6">
        <h2 id="modal-title-confirm-delete" class="text-lg font-semibold">
            Delete item?
        </h2>
        <p class="mt-2 text-sm text-gray-600">This action cannot be undone.</p>
    </div>
</x-livewindui::modal>
```

## Development

```bash
composer install
composer validate --strict
vendor/bin/pint --test
vendor/bin/pest
```

The sprint roadmap is in `docs/ROADMAP.md`, with sprint files in `docs/sprints/`.
