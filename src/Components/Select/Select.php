<?php

declare(strict_types=1);

namespace Livewind\Components\Select;

use Illuminate\View\Component;
use Illuminate\View\View;

class Select extends Component
{
    public function __construct(
        public mixed $model = null,
        public bool $modelLive = false,
        public mixed $label = null,
        public mixed $hint = null,
        public string $placeholder = 'Selecione...',
        public array $options = [],
    ) {}

    public function render(): View
    {
        return view('livewind::select');
    }
}
