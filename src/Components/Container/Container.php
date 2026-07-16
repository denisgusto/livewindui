<?php

declare(strict_types=1);

namespace Livewind\Components\Container;

use Illuminate\View\Component;
use Illuminate\View\View;

class Container extends Component
{
    public function __construct(
        public string $size = 'lg',
    ) {}

    public function render(): View
    {
        return view('livewind::container');
    }
}
