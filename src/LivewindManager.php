<?php

declare(strict_types=1);

namespace Livewind;

use Livewire\Component;

class LivewindManager
{
    public static function normalizeToast(
        string $message = '',
        ?string $title = null,
        string $variant = 'info',
        ?int $duration = null,
        ?string $text = null,
        ?string $heading = null,
    ): array {
        $payload = [
            'variant' => $variant === 'error' ? 'danger' : $variant,
            'title' => $title ?? $heading,
            'message' => $message !== '' ? $message : ($text ?? ''),
        ];

        if ($duration !== null) {
            $payload['duration'] = $duration;
        }

        return $payload;
    }

    public function toast(
        string $message = '',
        ?string $title = null,
        string $variant = 'info',
        ?int $duration = null,
        ?string $text = null,
        ?string $heading = null,
    ): void {
        $payload = self::normalizeToast($message, $title, $variant, $duration, $text, $heading);
        $component = app('livewire')->current();

        if ($component instanceof Component) {
            $component->dispatch('livewind:toast', ...$payload);

            return;
        }

        session()->push('livewind.toasts', $payload);
    }

    public function success(string $message, ?string $title = null, ?int $duration = null): void
    {
        $this->toast($message, $title, 'success', $duration);
    }

    public function info(string $message, ?string $title = null, ?int $duration = null): void
    {
        $this->toast($message, $title, 'info', $duration);
    }

    public function warning(string $message, ?string $title = null, ?int $duration = null): void
    {
        $this->toast($message, $title, 'warning', $duration);
    }

    public function danger(string $message, ?string $title = null, ?int $duration = null): void
    {
        $this->toast($message, $title, 'danger', $duration);
    }

    public function error(string $message, ?string $title = null, ?int $duration = null): void
    {
        $this->danger($message, $title, $duration);
    }

    public function modal(string $name): ModalController
    {
        return new ModalController($name);
    }

    public function modals(): ModalController
    {
        return new ModalController;
    }

    public function flashedToasts(): array
    {
        return session()->get('livewind.toasts', []);
    }
}
