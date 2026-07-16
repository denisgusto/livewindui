<?php

declare(strict_types=1);

namespace Livewind\Components\Breadcrumb;

use Illuminate\View\Component;
use Illuminate\View\View;

class BreadcrumbItem extends Component
{
    public function __construct(
        public mixed $href = null,
        public bool $current = false,
    ) {}

    public function render(): View
    {
        return view('livewind::breadcrumb-item');
    }
}
