<?php

namespace Livewind;

class LivewindManager
{
    public function boot(): void
    {
        // Boot logic for LivewindManager
    }

    public function renderIcon(string $name, string $class = 'size-5', string $variant = 'mini'): string
    {
        // Resolve o arquivo SVG do ícone
        $svgPath = __DIR__ . "/../resources/icons/{$variant}/{$name}.svg";

        if (! file_exists($svgPath)) {
            return ''; // ou lança exception, ou um placeholder
        }

        $svg = file_get_contents($svgPath);

        // Injeta a classe CSS no <svg>
        return str_replace(
            '<svg ',
            '<svg class="' . e($class) . '" aria-hidden="true" ',
            $svg
        );
    }
}
