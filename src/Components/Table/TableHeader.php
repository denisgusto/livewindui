<?php

declare(strict_types=1);

namespace Livewind\Components\Table;

use Illuminate\View\Component;
use Illuminate\View\View;

class TableHeader extends Component
{
    public function __construct(
        public bool $sortable = false,
        public bool $sorted = false,
        public string $direction = 'asc',
    ) {}

    public function render(): View
    {
        return view('livewind::table-header');
    }
}
