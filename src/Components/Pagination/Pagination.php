<?php

declare(strict_types=1);

namespace Livewind\Components\Pagination;

use Illuminate\View\Component;
use Illuminate\View\View;

class Pagination extends Component
{
    public function __construct(
        public mixed $paginator = null,
        public bool $compact = false,
    ) {}

    public function render(): View
    {
        return view('livewind::pagination');
    }
}
