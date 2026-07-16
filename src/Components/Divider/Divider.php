<?php

declare(strict_types=1);

namespace Livewind\Components\Divider;

use Illuminate\View\Component;
use Illuminate\View\View;

class Divider extends Component
{
    public function __construct(
        public mixed $label = null,
    ) {}

    public function render(): View
    {
        return view('livewind::divider');
    }
}
