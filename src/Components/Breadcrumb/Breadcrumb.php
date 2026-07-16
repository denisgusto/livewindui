<?php

declare(strict_types=1);

namespace Livewind\Components\Breadcrumb;

use Illuminate\View\Component;
use Illuminate\View\View;

class Breadcrumb extends Component
{
    public function __construct() {}

    public function render(): View
    {
        return view('livewind::breadcrumb');
    }
}
