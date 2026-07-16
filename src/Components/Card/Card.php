<?php

declare(strict_types=1);

namespace Livewind\Components\Card;

use Illuminate\View\Component;
use Illuminate\View\View;

class Card extends Component
{
    /**
     * @param  string  $variant  default | bordered | elevated
     */
    public function __construct(
        public string $variant = 'default',
    ) {}

    public function render(): View
    {
        return view('livewind::card');
    }

    public function classes(): string
    {
        return 'overflow-hidden rounded-lg '.$this->variantClasses();
    }

    private function variantClasses(): string
    {
        return match ($this->variant) {
            'bordered' => 'border border-border bg-surface',
            'elevated' => 'border border-border bg-surface shadow-md',
            default => 'border border-border bg-surface shadow-sm',
        };
    }
}
