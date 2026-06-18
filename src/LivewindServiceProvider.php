<?php

declare(strict_types=1);

namespace Livewind;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ComponentAttributeBag;
use Livewind\Livewind as LivewindFacade;

class LivewindServiceProvider extends ServiceProvider
{
    /* =====================================================================
     | Register
     * ===================================================================== */

    public function register(): void
    {
        $this->mergeConfigFrom($this->packageConfigPath(), 'livewind');

        $this->app->singleton(LivewindManager::class);
        $this->app->alias(LivewindManager::class, 'livewind');

        AliasLoader::getInstance()->alias('Livewind', LivewindFacade::class);
    }

    /* =====================================================================
     | Boot
     * ===================================================================== */

    public function boot(): void
    {
        $this->registerBladeComponents();
        $this->registerBladeDirectives();
        $this->registerMacros();
        $this->registerPublishing();
        $this->registerCommands();

        app('livewind')->boot();
    }

    protected function registerBladeComponents(): void
    {
        $prefix = $this->componentPrefix();

        // 1º: override do consumidor (se existir) — vence se o arquivo for encontrado
        $userPath = resource_path("views/{$prefix}");
        if (is_dir($userPath)) {
            Blade::anonymousComponentPath($userPath, $prefix);
        }

        // 2º: anonymous components da lib (default)
        Blade::anonymousComponentPath($this->packageComponentsPath(), $prefix);
    }

    protected function registerBladeDirectives(): void
    {
        Blade::directive(
            'lwIcon',
            fn($expr) =>
            "<?php echo app('livewind')->renderIcon($expr); ?>"
        );
    }

    protected function registerMacros(): void
    {
        ComponentAttributeBag::macro('pluck', function (string $key, mixed $default = null) {
            $value = $this->get($key, $default);
            unset($this->attributes[$key]);
            return $value;
        });
    }

    protected function registerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            $this->packageConfigPath() => config_path('livewind.php'),
        ], 'livewind-config');

        $this->publishes([
            $this->packageViewsPath() => resource_path('views/vendor/livewind'),
        ], 'livewind-views');

        $this->publishes([
            $this->packageCssPath() => resource_path('css/livewind.css'),
        ], 'livewind-theme');
    }

    protected function registerCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            // Console\InstallCommand::class,
            // Console\MakeComponentCommand::class,
        ]);
    }

    /* =====================================================================
     | Path helpers
     * ===================================================================== */

    protected function packageConfigPath(): string
    {
        return __DIR__ . '/../config/' . 'livewind.php';
    }

    protected function packageViewsPath(): string
    {
        return __DIR__ . '/../resources/views';
    }

    protected function packageComponentsPath(): string
    {
        return __DIR__ . '/../resources/views/components';
    }

    protected function packageCssPath(): string
    {
        return __DIR__ . '/../resources/css/' . 'livewind.css';
    }

    /* =====================================================================
     | Misc
     * ===================================================================== */

    protected function componentPrefix(): string
    {
        return (string) config('livewind.prefix', 'livewind');
    }
}
