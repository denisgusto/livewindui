<?php

declare(strict_types=1);

namespace Livewind\Components\Modal;

use Illuminate\View\Component;
use Illuminate\View\View;

class Modal extends Component
{
    public function __construct(
        public string $name = 'default',
        public mixed $maxWidth = null,
        public bool $closeable = true,
        public bool $show = false,
        public mixed $title = null,
    ) {}

    public function render(): View
    {
        return view('livewind::modal');
    }
}
