<?php

declare(strict_types=1);

namespace Livewind\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Badge extends Component
{
    /**
     * @param  string  $variant  neutral | success | info | warning | danger
     * @param  string  $size  xs | sm | md | lg
     * @param  bool|string  $dot  Leading status dot: true (static), 'pulse' or 'ping'
     * @param  string|null  $icon  Heroicon (mini) name, e.g. 'check-circle' (leading)
     */
    public function __construct(
        public string $variant = 'neutral',
        public string $size = 'md',
        public bool|string $dot = false,
        public ?string $icon = null,
    ) {}

    public function render(): View
    {
        return view('livewind::components.badge');
    }

    /* ---------------------------------------------------------------------
     | Public helpers used by the view
     * --------------------------------------------------------------------- */

    public function classes(): string
    {
        return $this->baseClasses().' '.$this->variantClasses().' '.$this->sizeClasses();
    }

    /**
     * Dimensao do dot (h/w), escalando com o tamanho do badge.
     */
    public function dotSize(): string
    {
        return match ($this->size) {
            'xs' => 'h-1 w-1',
            'lg' => 'h-2 w-2',
            default => 'h-1.5 w-1.5',
        };
    }

    /**
     * Tamanho do icone heroicon, escalando com o tamanho do badge.
     */
    public function iconSize(): string
    {
        return match ($this->size) {
            'xs' => 'size-2.5',
            'lg' => 'size-4',
            default => 'size-3',
        };
    }

    /**
     * Deve exibir o dot? (true, 'pulse' ou 'ping'; false/'' oculta).
     */
    public function hasDot(): bool
    {
        return $this->dot !== false && $this->dot !== '';
    }

    /**
     * Anima o dot com o efeito "ping" (anel expandindo atras do ponto solido).
     */
    public function isPing(): bool
    {
        return $this->dot === 'ping';
    }

    /**
     * Anima o dot com o efeito "pulse" (fade suave de opacidade).
     */
    public function isPulse(): bool
    {
        return $this->dot === 'pulse';
    }

    /**
     * Cor de fundo do dot (usada tanto no ponto solido quanto no anel do ping).
     */
    public function dotColor(): string
    {
        return match ($this->variant) {
            'success' => 'bg-success',
            'info' => 'bg-info',
            'warning' => 'bg-warning',
            'danger' => 'bg-danger',
            default => 'bg-muted-foreground',
        };
    }

    /* ---------------------------------------------------------------------
     | Class composition
     * --------------------------------------------------------------------- */

    private function baseClasses(): string
    {
        return 'inline-flex items-center rounded-full font-medium ring-1 ring-inset';
    }

    private function variantClasses(): string
    {
        return match ($this->variant) {
            'success' => 'bg-success/10 text-success ring-success/20',
            'info' => 'bg-info/10 text-info ring-info/20',
            'warning' => 'bg-warning/10 text-warning ring-warning/20',
            'danger' => 'bg-danger/10 text-danger ring-danger/20',
            default => 'bg-muted text-surface-foreground ring-border',
        };
    }

    private function sizeClasses(): string
    {
        return match ($this->size) {
            'xs' => 'gap-1 px-1.5 py-0.5 text-[0.625rem]',
            'sm' => 'gap-1 px-2 py-0.5 text-xs',
            'lg' => 'gap-1.5 px-3 py-1 text-sm',
            default => 'gap-1.5 px-2 py-1 text-xs',
        };
    }
}
