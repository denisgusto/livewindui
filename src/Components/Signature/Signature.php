<?php

declare(strict_types=1);

namespace Livewind\Components\Signature;

use Illuminate\View\Component;
use Illuminate\View\View;

class Signature extends Component
{
    /**
     * @param  string|null  $model  Livewire property to bind (stores a PNG data URL)
     * @param  string  $height  Tailwind height class for the canvas
     * @param  string  $penColor  Stroke color (any CSS color)
     */
    public function __construct(
        public ?string $model = null,
        public string $height = 'h-48',
        public string $penColor = '#111827',
    ) {}

    public function render(): View
    {
        return view('livewind::signature');
    }

    public function id(): string
    {
        return 'livewind-signature-'.md5((string) ($this->model ?? 'signature'));
    }
}
