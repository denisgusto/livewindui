<?php

declare(strict_types=1);

namespace Livewind\Components\Checkbox;

use Illuminate\View\Component;
use Illuminate\View\View;

class Checkbox extends Component
{
    public function __construct(
        public mixed $model = null,
        public mixed $label = null,
        public mixed $description = null,
        public mixed $value = null,
    ) {}

    public function render(): View
    {
        return view('livewind::checkbox');
    }
}
