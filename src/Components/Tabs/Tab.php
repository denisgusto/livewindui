<?php

declare(strict_types=1);

namespace Livewind\Components\Tabs;

use Illuminate\View\Component;
use Illuminate\View\View;

class Tab extends Component
{
    public function __construct(
        public string $name = 'default',
    ) {}

    public function render(): View
    {
        return view('livewind::tab');
    }
}
