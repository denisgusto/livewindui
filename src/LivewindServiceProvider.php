<?php

declare(strict_types=1);

namespace Livewind;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;
use Livewind\Facades\Livewind as LivewindFacade;

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
        $this->loadTranslationsFrom($this->packageLangPath(), 'livewind');

        $this->registerBladeComponents();
        $this->registerBladeDirectives();
        $this->registerMacros();
        $this->registerAssetRoute();
        $this->registerPublishing();
        $this->registerCommands();
    }

    /**
     * Serve o bundle JS (dist/livewind.js) por uma rota, estilo Livewire — assim o
     * consumidor não precisa rodar npm nem publicar assets. Injetado por @livewindScripts.
     */
    protected function registerAssetRoute(): void
    {
        Route::get('/livewind/livewind.js', function () {
            return response()->file($this->packageBundlePath(), [
                'Content-Type' => 'text/javascript; charset=utf-8',
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        })->name('livewind.js');
    }

    /**
     * Diretivas de layout (estilo Flux): o consumidor adiciona uma vez e o
     * runtime da lib fica configurado.
     *
     * @livewindAppearance  no <head> — script anti-flash de dark mode (removivel).
     *
     * @livewindScripts     antes de </body> — container global de toast + runtime.
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive(
            'livewindAppearance',
            fn () => "<?php echo view('livewind::runtime.appearance')->render(); ?>"
        );

        Blade::directive(
            'livewindScripts',
            fn () => "<?php echo view('livewind::runtime.scripts')->render(); ?>"
        );
    }

    protected function registerBladeComponents(): void
    {
        $prefix = $this->componentPrefix();
        $this->loadViewsFrom($this->packageViewsPath(), 'livewind');
        $this->loadViewsFrom($this->packageViewsPath(), $prefix);

        // 1º: override do consumidor (se existir) — vence se o arquivo for encontrado
        $userPath = resource_path("views/{$prefix}");
        if (is_dir($userPath)) {
            Blade::anonymousComponentPath($userPath, $prefix);
        }

        // 2º: componentes colocados (src/Components/<Nome>/)
        $this->registerColocatedComponents($prefix);
    }

    /**
     * Auto-discovery dos componentes colocados: cada pasta em src/Components/<Nome>/
     * traz a classe (<Nome>.php) e as views (<kebab>.blade.php + sub-views). As views
     * entram no namespace `livewind::`; a classe é registrada como <prefix>::<kebab>;
     * sub-views triviais resolvem como componentes anônimos daquela pasta.
     */
    protected function registerColocatedComponents(string $prefix): void
    {
        foreach (glob($this->packageColocatedPath().'/*', GLOB_ONLYDIR) as $dir) {
            $folder = basename($dir);

            // Views colocadas (<kebab>.blade.php + sub-views) no namespace livewind::
            // e como componentes anônimos daquela pasta (fallback p/ sub-views triviais).
            $this->loadViewsFrom($dir, 'livewind');
            Blade::anonymousComponentPath($dir, $prefix);

            // Registra TODA classe da pasta (a homônima + sub-componentes da família).
            foreach (glob($dir.'/*.php') as $classFile) {
                $class = "Livewind\\Components\\{$folder}\\".basename($classFile, '.php');
                if (class_exists($class)) {
                    Blade::component($class, $prefix.'::'.Str::kebab(class_basename($class)));
                }
            }
        }
    }

    protected function registerMacros(): void
    {
        ComponentAttributeBag::macro('pluck', function (string $key, mixed $default = null) {
            $value = $this->get($key, $default);
            $this->offsetUnset($key);

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

        $this->publishes([
            $this->packageLangPath() => lang_path('vendor/livewind'),
        ], 'livewind-lang');
    }

    protected function registerCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Console\InstallCommand::class,
        ]);
    }

    /* =====================================================================
     | Path helpers
     * ===================================================================== */

    protected function packageConfigPath(): string
    {
        return __DIR__.'/../config/'.'livewind.php';
    }

    protected function packageViewsPath(): string
    {
        return __DIR__.'/../resources/views';
    }

    protected function packageColocatedPath(): string
    {
        return __DIR__.'/Components';
    }

    protected function packageBundlePath(): string
    {
        return __DIR__.'/../dist/livewind.js';
    }

    protected function packageLangPath(): string
    {
        return __DIR__.'/../lang';
    }

    protected function packageCssPath(): string
    {
        return __DIR__.'/../resources/css/livewind.css';
    }

    /* =====================================================================
     | Misc
     * ===================================================================== */

    protected function componentPrefix(): string
    {
        return (string) config('livewind.prefix', 'livewind');
    }
}
