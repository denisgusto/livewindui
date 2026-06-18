<?php

declare(strict_types=1);

namespace LiveWindUi;

/**
 * Ponto de entrada programatico da LiveWindUI, exposto pela facade `Livewind`.
 *
 * Dispara eventos no componente Livewire atualmente em execucao, espelhando a
 * API do FluxUI (Livewind::toast(...), Livewind::modal('x')->show(), ...). Fora
 * de um ciclo Livewire (ex.: controller antes de um redirect), os toasts ficam
 * em flash de sessao e sao exibidos no proximo carregamento de pagina.
 */
class LiveWindUiManager
{
    /**
     * Chave de sessao usada para enfileirar toasts entre requisicoes.
     */
    public const FLASH_KEY = 'livewindui::toasts';

    /**
     * Dispara um toast pelo evento `livewindui:toast`.
     *
     * Suporta os nomes nativos (message/title) e os aliases do FluxUI
     * (text/heading). `duration` em milissegundos; 0 = toast permanente.
     */
    public function toast(
        string $message = '',
        ?string $title = null,
        string $variant = 'info',
        ?int $duration = null,
        ?string $text = null,
        ?string $heading = null,
    ): void {
        $payload = self::normalizeToast($message, $title, $variant, $duration, $text, $heading);

        if (app('livewire')->current()) {
            $this->dispatch('livewindui:toast', $payload);

            return;
        }

        $this->flashToast($payload);
    }

    public function success(string $message = '', ?string $title = null, ?int $duration = null): void
    {
        $this->toast(message: $message, title: $title, variant: 'success', duration: $duration);
    }

    public function info(string $message = '', ?string $title = null, ?int $duration = null): void
    {
        $this->toast(message: $message, title: $title, variant: 'info', duration: $duration);
    }

    public function warning(string $message = '', ?string $title = null, ?int $duration = null): void
    {
        $this->toast(message: $message, title: $title, variant: 'warning', duration: $duration);
    }

    public function danger(string $message = '', ?string $title = null, ?int $duration = null): void
    {
        $this->toast(message: $message, title: $title, variant: 'danger', duration: $duration);
    }

    /**
     * Alias para danger(), no estilo "error" comum em libs de notificacao.
     */
    public function error(string $message = '', ?string $title = null, ?int $duration = null): void
    {
        $this->danger($message, $title, $duration);
    }

    /**
     * Controla um modal nomeado: Livewind::modal('confirm')->show()/->close().
     */
    public function modal(string $name = 'default'): object
    {
        return new class($name, $this)
        {
            public function __construct(
                private string $name,
                private LiveWindUiManager $manager,
            ) {}

            public function show(): void
            {
                $this->manager->dispatch('livewindui-modal-open', ['name' => $this->name]);
            }

            public function open(): void
            {
                $this->show();
            }

            public function close(): void
            {
                $this->manager->dispatch('livewindui-modal-close', ['name' => $this->name]);
            }
        };
    }

    /**
     * Controla todos os modais de uma vez: Livewind::modals()->close().
     */
    public function modals(): object
    {
        return new class($this)
        {
            public function __construct(private LiveWindUiManager $manager) {}

            public function close(): void
            {
                $this->manager->dispatch('livewindui-modal-close');
            }
        };
    }

    /**
     * Toasts enfileirados em flash, consumidos pelo container no carregamento.
     *
     * @return array<int, array<string, mixed>>
     */
    public function flashedToasts(): array
    {
        if (! app()->bound('session')) {
            return [];
        }

        return array_values(app('session')->get(self::FLASH_KEY, []));
    }

    /**
     * Normaliza os argumentos de um toast para o payload final do evento.
     *
     * @return array<string, mixed>
     */
    public static function normalizeToast(
        string $message = '',
        ?string $title = null,
        string $variant = 'info',
        ?int $duration = null,
        ?string $text = null,
        ?string $heading = null,
    ): array {
        return array_filter(
            [
                'variant' => $variant,
                'title' => $title ?? $heading,
                'message' => $message !== '' ? $message : $text,
                'duration' => $duration,
            ],
            static fn ($value): bool => $value !== null,
        );
    }

    /**
     * Dispara um evento no componente Livewire atual, se houver um em execucao.
     *
     * @param  array<string, mixed>  $params
     */
    public function dispatch(string $event, array $params = []): void
    {
        $component = app('livewire')->current();

        if ($component) {
            $component->dispatch($event, ...$params);
        }
    }

    /**
     * Guarda um toast em flash de sessao para o proximo carregamento de pagina.
     *
     * @param  array<string, mixed>  $payload
     */
    protected function flashToast(array $payload): void
    {
        if (! app()->bound('session')) {
            return;
        }

        $session = app('session');
        $toasts = $session->get(self::FLASH_KEY, []);
        $toasts[] = $payload;

        $session->flash(self::FLASH_KEY, $toasts);
    }
}
