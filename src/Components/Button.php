<?php

declare(strict_types=1);

namespace Livewind\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Button extends Component
{
    /**
     * @param  string  $variant  outline | primary | filled | danger | ghost | subtle
     * @param  string  $size  base | sm | xs
     * @param  string|null  $icon  Heroicon name (leading)
     * @param  string|null  $iconTrailing  Heroicon name (trailing)
     * @param  string  $as  button | a | div
     * @param  string|null  $href  URL when used as anchor
     * @param  string  $type  button | submit
     * @param  string  $align  center | start | end
     * @param  bool|null  $square  Force square shape (auto for icon-only)
     * @param  string|null  $inset  Sides for negative margin: 'top', 'left top', etc.
     * @param  string|null  $tooltip  Tooltip text on hover
     * @param  string|null  $kbd  Keyboard shortcut to display
     * @param  bool  $loading  Auto-show spinner on wire:click / type=submit
     */
    public function __construct(
        public string $variant = 'primary',
        public string $size = 'md',
        public ?string $icon = null,
        public ?string $iconTrailing = null,
        public string $as = 'button',
        public ?string $href = null,
        public string $type = 'button',
        public string $align = 'center',
        public ?bool $square = null,
        public ?string $inset = null,
        public ?string $tooltip = null,
        public ?string $kbd = null,
        public bool|string $loading = true,
    ) {}

    public function render(): View
    {
        return view('livewind::components.button');
    }

    /* ---------------------------------------------------------------------
     | Public helpers used by the view
     * --------------------------------------------------------------------- */

    public function tag(): string
    {
        return $this->href ? 'a' : $this->as;
    }

    public function classes(): string
    {
        return collect([
            $this->baseClasses(),
            $this->variantClasses(),
            $this->sizeClasses(),
            $this->alignClasses(),
            $this->insetClasses(),
        ])->filter()->implode(' ');
    }

    public function isSquare(bool $hasContent): bool
    {
        // Explicit prop wins; otherwise auto-square when icon-only
        return $this->square ?? (! $hasContent && ($this->icon || $this->iconTrailing));
    }

    public function shouldShowLoading($attributes): bool
    {
        if (! $this->loading) {
            return false;
        }

        return $attributes->whereStartsWith('wire:click')->getAttributes() !== []
            || $this->type === 'submit'
            || is_string($this->loading);
    }

    public function loadingTarget($attributes): ?string
    {
        if (is_string($this->loading)) {
            return $this->loading;
        }

        return $attributes->whereStartsWith('wire:click')->first();
    }

    public function iconSize(): string
    {
        return match ($this->size) {
            'sm', 'xs' => 'size-3.5',
            default => 'size-4',
        };
    }

    /* ---------------------------------------------------------------------
     | Class composition
     * --------------------------------------------------------------------- */

    private function baseClasses(): string
    {
        return 'inline-flex items-center gap-2 font-medium rounded-md whitespace-nowrap '
             .'cursor-pointer transition-colors '
             .'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent '
             .'disabled:opacity-50 disabled:pointer-events-none '
             .'aria-[busy=true]:pointer-events-none';
    }

    private function variantClasses(): string
    {
        return match ($this->variant) {
            'primary' => 'bg-accent text-accent-foreground hover:bg-accent/90',

            'filled', 'secondary' => 'bg-muted text-surface-foreground hover:bg-muted/80',

            'danger' => 'bg-danger text-danger-foreground hover:bg-danger/90',

            'ghost' => 'text-surface-foreground hover:bg-muted',

            'subtle' => 'text-muted-foreground hover:bg-muted hover:text-surface-foreground',

            default => 'border border-border bg-surface text-surface-foreground hover:bg-muted',
        };
    }

    private function sizeClasses(): string
    {
        // Width is overridden in the view when the button is auto-detected as square.
        return match ($this->size) {
            'xs' => 'h-7 px-2 text-xs',
            'sm' => 'h-8 px-3 py-1.5 text-sm',
            'lg' => 'h-11 px-5 py-2.5 text-base',
            default => 'h-10 px-4 py-2 text-sm',
        };
    }

    private function alignClasses(): string
    {
        return match ($this->align) {
            'start' => 'justify-start',
            'end' => 'justify-end',
            default => 'justify-center',
        };
    }

    private function insetClasses(): ?string
    {
        if (! $this->inset) {
            return null;
        }

        $map = [
            'top' => '-mt-1',
            'bottom' => '-mb-1',
            'left' => '-ml-1',
            'right' => '-mr-1',
        ];

        $sides = preg_split('/\s+/', trim($this->inset));

        return collect($sides)
            ->map(fn ($side) => $map[$side] ?? null)
            ->filter()
            ->implode(' ');
    }
}
