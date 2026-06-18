<?php

declare(strict_types=1);

namespace LiveWindUi;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use LiveWindUi\Components\Data\DataTable;
use LiveWindUi\Facades\Livewind as LivewindFacade;

class LiveWindUiServiceProvider extends ServiceProvider
{
    /**
     * Register package services and merge the default config.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/livewindui.php', 'livewindui');

        $this->app->singleton('livewind', fn (): LiveWindUiManager => new LiveWindUiManager);
        $this->app->alias('livewind', LiveWindUiManager::class);
    }

    /**
     * Bootstrap the package views and anonymous Blade components.
     */
    public function boot(): void
    {
        $prefix = $this->componentPrefix();

        $this->loadViewsFrom(__DIR__.'/../resources/views', $prefix);

        $this->registerBladeComponents($prefix);
        $this->registerFacadeAlias();
        $this->registerPublishing();
    }

    /**
     * Expose the `Livewind` facade alias globally (Livewind::toast(), Livewind::modal()).
     */
    protected function registerFacadeAlias(): void
    {
        if (class_exists(AliasLoader::class)) {
            AliasLoader::getInstance()->alias('Livewind', LivewindFacade::class);
        }
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

        $this->publishes([
            __DIR__.'/../resources/css/livewindui.css' => resource_path('css/livewindui.css'),
            __DIR__.'/../tailwind.preset.js' => base_path('tailwind.preset.livewindui.js'),
        ], 'livewindui-theme');
    }

    protected function componentPrefix(): string
    {
        return (string) config('livewindui.prefix', 'livewindui');
    }
}
