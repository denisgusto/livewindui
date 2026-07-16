<?php

declare(strict_types=1);

namespace Livewind\Components\Spinner;

use Illuminate\View\Component;
use Illuminate\View\View;

class Spinner extends Component
{
    public function __construct(
        public string $size = 'md',
    ) {}

    public function render(): View
    {
        return view('livewind::spinner');
    }
}
