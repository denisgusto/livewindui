<?php

declare(strict_types=1);

namespace Livewind\Concerns;

use Livewind\LiveWindUiManager;

/**
 * Dispara toasts a partir de um componente Livewire via evento `livewindui:toast`.
 *
 * Suporta os nomes nativos (message/title) e os aliases inspirados no FluxUI
 * (text/heading). `duration` em milissegundos; use 0 para um toast permanente.
 */
trait InteractsWithToasts
{
    public function toast(
        string $message = '',
        ?string $title = null,
        string $variant = 'info',
        ?int $duration = null,
        ?string $text = null,
        ?string $heading = null,
    ): void {
        $this->dispatch(
            'livewindui:toast',
            ...LiveWindUiManager::normalizeToast($message, $title, $variant, $duration, $text, $heading),
        );
    }
}
