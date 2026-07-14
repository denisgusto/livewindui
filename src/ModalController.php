<?php

declare(strict_types=1);

namespace Livewind;

use Livewire\Component;

final class ModalController
{
    public function __construct(private readonly ?string $name = null) {}

    public function show(): void
    {
        $this->dispatch('livewind-modal-open', ['name' => $this->name]);
    }

    public function open(): void
    {
        $this->show();
    }

    public function close(): void
    {
        $payload = $this->name === null ? [] : ['name' => $this->name];
        $this->dispatch('livewind-modal-close', $payload);
    }

    private function dispatch(string $event, array $payload): void
    {
        $component = app('livewire')->current();

        if ($component instanceof Component) {
            $component->dispatch($event, ...$payload);
        }
    }
}
