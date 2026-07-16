<?php

declare(strict_types=1);

namespace Livewind\Components\Toast;

use Illuminate\View\Component;
use Illuminate\View\View;

class ToastItem extends Component
{
    public function __construct(
        public string $variant = 'info',
        public mixed $title = null,
        public mixed $message = null,
        public bool $dismissible = true,
    ) {}

    public function render(): View
    {
        return view('livewind::toast-item');
    }
}
