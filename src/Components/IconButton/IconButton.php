<?php

declare(strict_types=1);

namespace Livewind\Components\IconButton;

use Illuminate\View\Component;
use Illuminate\View\View;

class IconButton extends Component
{
    public function __construct(
        public string $variant = 'ghost',
        public string $size = 'md',
        public mixed $label = null,
        public string $type = 'button',
    ) {}

    public function render(): View
    {
        return view('livewind::icon-button');
    }
}
