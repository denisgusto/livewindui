<?php

declare(strict_types=1);

namespace Livewind\Components\Table;

use Illuminate\View\Component;
use Illuminate\View\View;

class TableRow extends Component
{
    public function __construct(
        public bool $selected = false,
    ) {}

    public function render(): View
    {
        return view('livewind::table-row');
    }
}
