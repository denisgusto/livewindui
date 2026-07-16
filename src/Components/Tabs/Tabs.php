<?php

declare(strict_types=1);

namespace Livewind\Components\Tabs;

use Illuminate\View\Component;
use Illuminate\View\View;

class Tabs extends Component
{
    public function __construct(
        public string $defaultTab = 'default',
        public bool $serverSide = false,
    ) {}

    public function render(): View
    {
        return view('livewind::tabs');
    }
}
