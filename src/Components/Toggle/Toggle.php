<?php

declare(strict_types=1);

namespace Livewind\Components\Toggle;

use Illuminate\View\Component;
use Illuminate\View\View;

class Toggle extends Component
{
    public function __construct(
        public mixed $model = null,
        public mixed $label = null,
        public mixed $description = null,
        public string $size = 'md',
    ) {}

    public function render(): View
    {
        return view('livewind::toggle');
    }
}
