<?php

declare(strict_types=1);

namespace Livewind\Components\Radio;

use Illuminate\View\Component;
use Illuminate\View\View;

class Radio extends Component
{
    public function __construct(
        public mixed $model = null,
        public mixed $label = null,
        public mixed $description = null,
        public mixed $value = null,
    ) {}

    public function render(): View
    {
        return view('livewind::radio');
    }
}
