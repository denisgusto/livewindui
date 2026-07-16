<?php

declare(strict_types=1);

namespace Livewind\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'livewind:install {--force : Overwrite files that already exist}';

    protected $description = 'Install LiveWindUI: publish the theme/config and wire up the Tailwind imports.';

    public function handle(): int
    {
        $this->components->info('Installing LiveWindUI…');

        $force = (bool) $this->option('force');
        $this->callSilently('vendor:publish', ['--tag' => 'livewind-theme', '--force' => $force]);
        $this->callSilently('vendor:publish', ['--tag' => 'livewind-config', '--force' => $force]);

        $this->wireTailwindImports();
        $this->printLayoutHelp();

        $this->newLine();
        $this->components->info('LiveWindUI installed.');

        return self::SUCCESS;
    }

    /**
     * Injeta os @import/@source no app.css do consumidor (se existir).
     */
    private function wireTailwindImports(): void
    {
        $appCss = resource_path('css/app.css');

        if (! is_file($appCss)) {
            $this->components->warn('resources/css/app.css not found — add the imports manually (see README).');

            return;
        }

        $contents = (string) file_get_contents($appCss);
        $marker = 'vendor/denisgusto/livewindui/resources/css/livewind.css';

        if (str_contains($contents, $marker)) {
            $this->components->info('app.css already imports the LiveWindUI theme.');

            return;
        }

        $imports = <<<'CSS'

        @import "../../vendor/denisgusto/livewindui/resources/css/livewind.css";
        @source "../../vendor/denisgusto/livewindui/src/Components";
        CSS;

        file_put_contents($appCss, rtrim($contents).PHP_EOL.$imports.PHP_EOL);
        $this->components->info('Added the theme import + @source to resources/css/app.css.');
    }

    private function printLayoutHelp(): void
    {
        $this->newLine();
        $this->components->info('Add these directives to your layout:');
        $this->line('  <head> … <span class="text-yellow-500">@livewindAppearance</span> </head>   # optional dark-mode script');
        $this->line('  <body> … <span class="text-yellow-500">@livewindScripts</span> </body>      # toast container + runtime');
        $this->newLine();
        $this->line('  Optional Alpine plugins (only if you use them):');
        $this->line('    • <x-livewind::input mask> → @alpinejs/mask');
        $this->line('    • <x-livewind::modal>      → @alpinejs/focus');
    }
}
