<?php

declare(strict_types=1);

namespace LiveWindUi;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use LiveWindUi\Components\Data\DataTable;

class LiveWindUiServiceProvider extends ServiceProvider
{
    /**
     * Register package services and merge the default config.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/livewindui.php', 'livewindui');
    }

    /**
     * Bootstrap the package views and anonymous Blade components.
     */
    public function boot(): void
    {
        $prefix = $this->componentPrefix();

        $this->loadViewsFrom(__DIR__.'/../resources/views', $prefix);

        $this->registerBladeComponents($prefix);
        $this->registerPublishing();
    }

    /**
     * Anonymous Blade components: <x-livewindui::button />, <x-livewindui::input />, ...
     */
    protected function registerBladeComponents(string $prefix): void
    {
        Blade::component(DataTable::class, "{$prefix}::data-table");

        Blade::anonymousComponentPath(
            __DIR__.'/../resources/views/components',
            $prefix
        );
    }

    /**
     * Allow consumers to publish the config and override the views.
     */
    protected function registerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/livewindui.php' => config_path('livewindui.php'),
        ], 'livewindui-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/livewindui'),
        ], 'livewindui-views');
    }

    protected function componentPrefix(): string
    {
        return (string) config('livewindui.prefix', 'livewindui');
    }
}
