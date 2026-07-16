<?php

declare(strict_types=1);

namespace Livewind\Components\Table;

use Illuminate\View\Component;
use Illuminate\View\View;

class Table extends Component
{
    public function __construct(
        public bool $striped = false,
        public bool $hover = true,
        public bool $compact = false,
    ) {}

    public function render(): View
    {
        return view('livewind::table');
    }
}
