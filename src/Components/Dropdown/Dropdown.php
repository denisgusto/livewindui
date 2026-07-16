<?php

declare(strict_types=1);

namespace Livewind\Components\Dropdown;

use Illuminate\View\Component;
use Illuminate\View\View;

class Dropdown extends Component
{
    public function __construct(
        public string $align = 'right',
        public string $width = 'md',
    ) {}

    public function render(): View
    {
        return view('livewind::dropdown');
    }
}
