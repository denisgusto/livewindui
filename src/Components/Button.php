<?php

namespace Livewind\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Button extends Component
{
    /**
     * @param  string       $variant       outline | primary | filled | danger | ghost | subtle
     * @param  string       $color         zinc | red | orange | amber | yellow | lime | green |
     *                                     emerald | teal | cyan | sky | blue | indigo | violet |
     *                                     purple | fuchsia | pink | rose | accent
     * @param  string       $size          base | sm | xs
     * @param  string|null  $icon          Heroicon name (leading)
     * @param  string|null  $iconTrailing  Heroicon name (trailing)
     * @param  string       $as            button | a | div
     * @param  string|null  $href          URL when used as anchor
     * @param  string       $type          button | submit
     * @param  string       $align         center | start | end
     * @param  bool|null    $square        Force square shape (auto for icon-only)
     * @param  string|null  $inset         Sides for negative margin: 'top', 'left top', etc.
     * @param  string|null  $tooltip       Tooltip text on hover
     * @param  string|null  $kbd           Keyboard shortcut to display
     * @param  bool         $loading       Auto-show spinner on wire:click / type=submit
     */
    public function __construct(
        public string $variant = 'outline',
        public string $color = 'accent',
        public string $size = 'base',
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
        public bool $loading = true,
    ) {
    }

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

        return $attributes->whereStartsWith('wire:click')->count() > 0
            || $this->type === 'submit';
    }

    public function iconSize(): string
    {
        return match ($this->size) {
            'xs' => 'size-3.5',
            'sm' => 'size-4',
            default => 'size-4',
        };
    }

    /* ---------------------------------------------------------------------
     | Class composition
     * --------------------------------------------------------------------- */

    private function baseClasses(): string
    {
        return 'inline-flex items-center gap-2 font-medium rounded-md whitespace-nowrap '
             . 'cursor-pointer transition-colors '
             . 'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent '
             . 'disabled:opacity-50 disabled:pointer-events-none '
             . 'aria-[busy=true]:pointer-events-none';
    }

    private function variantClasses(): string
    {
        return match ($this->variant) {
            'primary' => $this->primaryColorClasses(),

            'filled'  => 'bg-zinc-100 text-zinc-900 hover:bg-zinc-200 '
                       . 'dark:bg-zinc-700 dark:text-white dark:hover:bg-zinc-600',

            'danger'  => 'bg-danger text-danger-foreground hover:bg-danger/90',

            'ghost'   => 'text-zinc-700 hover:bg-zinc-100 '
                       . 'dark:text-zinc-300 dark:hover:bg-zinc-800',

            'subtle'  => 'text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 '
                       . 'dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100',

            default   => 'border border-zinc-200 bg-white text-zinc-900 hover:bg-zinc-50 '
                       . 'dark:border-zinc-700 dark:bg-zinc-900 dark:text-white dark:hover:bg-zinc-800',
        };
    }

    /**
     * Primary variant supports the full Tailwind palette via the `color` prop.
     * These strings are scanned by Tailwind via the @source directive in lw-theme.css.
     */
    private function primaryColorClasses(): string
    {
        return match ($this->color) {
            'red'     => 'bg-red-600 text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-400',
            'orange'  => 'bg-orange-600 text-white hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-400',
            'amber'   => 'bg-amber-500 text-white hover:bg-amber-600 dark:bg-amber-400 dark:text-zinc-900 dark:hover:bg-amber-300',
            'yellow'  => 'bg-yellow-400 text-zinc-900 hover:bg-yellow-500 dark:bg-yellow-300 dark:hover:bg-yellow-200',
            'lime'    => 'bg-lime-500 text-zinc-900 hover:bg-lime-600 dark:bg-lime-400 dark:hover:bg-lime-300',
            'green'   => 'bg-green-600 text-white hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400',
            'emerald' => 'bg-emerald-600 text-white hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-400',
            'teal'    => 'bg-teal-600 text-white hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-400',
            'cyan'    => 'bg-cyan-600 text-white hover:bg-cyan-700 dark:bg-cyan-500 dark:hover:bg-cyan-400',
            'sky'     => 'bg-sky-600 text-white hover:bg-sky-700 dark:bg-sky-500 dark:hover:bg-sky-400',
            'blue'    => 'bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400',
            'indigo'  => 'bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400',
            'violet'  => 'bg-violet-600 text-white hover:bg-violet-700 dark:bg-violet-500 dark:hover:bg-violet-400',
            'purple'  => 'bg-purple-600 text-white hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-400',
            'fuchsia' => 'bg-fuchsia-600 text-white hover:bg-fuchsia-700 dark:bg-fuchsia-500 dark:hover:bg-fuchsia-400',
            'pink'    => 'bg-pink-600 text-white hover:bg-pink-700 dark:bg-pink-500 dark:hover:bg-pink-400',
            'rose'    => 'bg-rose-600 text-white hover:bg-rose-700 dark:bg-rose-500 dark:hover:bg-rose-400',
            'zinc'    => 'bg-zinc-800 text-white hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100',
            default   => 'bg-accent text-accent-foreground hover:bg-accent/90',
        };
    }

    private function sizeClasses(): string
    {
        // Width is overridden in the view when the button is auto-detected as square.
        return match ($this->size) {
            'xs' => 'h-7 px-2 text-xs',
            'sm' => 'h-8 px-3 text-sm',
            default => 'h-10 px-4 text-sm',
        };
    }

    private function alignClasses(): string
    {
        return match ($this->align) {
            'start' => 'justify-start',
            'end'   => 'justify-end',
            default => 'justify-center',
        };
    }

    private function insetClasses(): ?string
    {
        if (! $this->inset) {
            return null;
        }

        $map = [
            'top'    => '-mt-1',
            'bottom' => '-mb-1',
            'left'   => '-ml-1',
            'right'  => '-mr-1',
        ];

        $sides = preg_split('/\s+/', trim($this->inset));

        return collect($sides)
            ->map(fn ($side) => $map[$side] ?? null)
            ->filter()
            ->implode(' ');
    }
}