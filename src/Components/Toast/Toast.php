<?php

declare(strict_types=1);

namespace Livewind\Components\Toast;

use Illuminate\View\Component;
use Illuminate\View\View;

class Toast extends Component
{
    public function __construct(
        public mixed $position = null,
        public mixed $duration = null,
        public mixed $max = null,
    ) {}

    public function render(): View
    {
        return view('livewind::toast');
    }
}
