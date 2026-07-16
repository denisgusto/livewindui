<?php

declare(strict_types=1);

namespace Livewind\Components\Textarea;

use Illuminate\View\Component;
use Illuminate\View\View;

class Textarea extends Component
{
    public function __construct(
        public mixed $model = null,
        public bool $modelLive = false,
        public mixed $label = null,
        public mixed $hint = null,
        public int $rows = 4,
        public mixed $maxLength = null,
        public bool $autoResize = false,
    ) {}

    public function render(): View
    {
        return view('livewind::textarea');
    }
}
