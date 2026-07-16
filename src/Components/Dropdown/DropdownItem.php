<?php

declare(strict_types=1);

namespace Livewind\Components\Dropdown;

use Illuminate\View\Component;
use Illuminate\View\View;

class DropdownItem extends Component
{
    public function __construct(
        public mixed $href = null,
        public string $type = 'button',
    ) {}

    public function render(): View
    {
        return view('livewind::dropdown-item');
    }
}
