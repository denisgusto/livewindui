<?php

declare(strict_types=1);

namespace Livewind\Components\Alert;

use Illuminate\View\Component;
use Illuminate\View\View;

class Alert extends Component
{
    public function __construct(
        public string $variant = 'info',
        public mixed $title = null,
        public bool $dismissible = false,
        public mixed $autoDismiss = null,
    ) {}

    public function render(): View
    {
        return view('livewind::alert');
    }
}
