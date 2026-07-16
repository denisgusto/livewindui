<?php

declare(strict_types=1);

namespace Livewind\Components\Input;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\View;

class Input extends Component
{
    /**
     * @param  string|null  $model  Livewire property to bind via wire:model
     * @param  bool  $modelLive  Use wire:model.live instead of deferred
     * @param  string|null  $label  Visible field label
     * @param  string|null  $hint  Helper text shown below the field
     * @param  string  $type  Native input type (text, email, password, ...)
     * @param  string|null  $mask  Alpine x-mask template, e.g. '(99) 99999-9999'.
     *                             Requires the @alpinejs/mask plugin in the app.
     *                             For dynamic masks (money) pass x-mask:dynamic directly.
     */
    public function __construct(
        public ?string $model = null,
        public bool $modelLive = false,
        public ?string $label = null,
        public ?string $hint = null,
        public string $type = 'text',
        public ?string $mask = null,
    ) {}

    public function render(): View
    {
        return view('livewind::input');
    }

    /* ---------------------------------------------------------------------
     | Public helpers used by the view
     * --------------------------------------------------------------------- */

    /**
     * Id do input: respeita um id informado pelo consumidor ou gera um estavel.
     */
    public function id(): string
    {
        return $this->attributes->get('id')
            ?? 'livewind-input-'.md5((string) ($this->model ?? $this->label ?? 'field'));
    }

    public function descriptionId(): string
    {
        return $this->id().'-description';
    }

    public function hasError(): bool
    {
        return filled($this->model) && $this->errorBag()->has($this->model);
    }

    public function errorMessage(): ?string
    {
        return filled($this->model) ? $this->errorBag()->first($this->model) : null;
    }

    /**
     * Monta o attribute bag do <input>, incluindo classes de estado, aria-*,
     * padding para slots laterais e o binding wire:model.
     */
    public function inputAttributes(bool $hasPrefix = false, bool $hasSuffix = false): ComponentAttributeBag
    {
        $hasError = $this->hasError();

        $attributes = $this->attributes
            ->except(['wire:model', 'wire:model.live'])
            ->class([
                'block w-full rounded-md border px-3 py-2 text-sm text-surface-foreground shadow-sm transition placeholder:text-muted-foreground focus:outline-none focus:ring-2 disabled:cursor-not-allowed disabled:bg-muted disabled:text-muted-foreground',
                'border-border focus:border-accent focus:ring-accent' => ! $hasError,
                'border-danger focus:border-danger focus:ring-danger' => $hasError,
                'pl-10' => $hasPrefix,
                'pr-10' => $hasSuffix,
            ])
            ->merge([
                'id' => $this->id(),
                'type' => $this->type,
                'aria-invalid' => $hasError ? 'true' : 'false',
                'aria-describedby' => ($hasError || filled($this->hint)) ? $this->descriptionId() : null,
            ]);

        if ($wireModel = $this->wireModelAttribute()) {
            $attributes = $attributes->merge([$wireModel => $this->model]);
        }

        // Alias fino para o x-mask do Alpine (o app precisa do plugin @alpinejs/mask).
        if (filled($this->mask)) {
            $attributes = $attributes->merge(['x-mask' => $this->mask]);
        }

        return $attributes;
    }

    /* ---------------------------------------------------------------------
     | Internals
     * --------------------------------------------------------------------- */

    /**
     * Nome do atributo wire:model (deferido ou .live), ou null sem model.
     */
    private function wireModelAttribute(): ?string
    {
        if (! filled($this->model)) {
            return null;
        }

        return $this->modelLive ? 'wire:model.live' : 'wire:model';
    }

    /**
     * Error bag compartilhado com as views (fallback vazio fora de request).
     */
    private function errorBag(): ViewErrorBag
    {
        $errors = app('view')->shared('errors');

        return $errors instanceof ViewErrorBag ? $errors : new ViewErrorBag;
    }
}
