<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Livewire\Livewire;

it('renders modal shell with event listeners and aria attributes', function () {
    $html = Blade::render(<<<'BLADE'
        <x-livewind::modal name="confirm-delete" max-width="sm" title="Confirmar exclusão?">
            <div class="p-6">
                <p>Esta ação não pode ser desfeita.</p>
            </div>
        </x-livewind::modal>
    BLADE);

    expect($html)
        ->toContain('role="dialog"')
        ->toContain('aria-modal="true"')
        ->toContain('aria-labelledby="modal-title-confirm-delete"')
        ->toContain('id="modal-title-confirm-delete"')
        ->toContain('x-ref="panel"')
        ->toContain('this.trigger?.focus()')
        ->toContain('x-on:livewind-modal-open.window')
        ->toContain('x-on:livewind-modal-close.window')
        ->toContain('x-on:keydown.escape.window="close()"')
        ->toContain('x-trap.noscroll="show"')
        ->toContain('max-w-sm');
});

it('supports initial show state and custom max width', function () {
    $html = Blade::render('<x-livewind::modal name="edit" max-width="2xl" :show="true">Editar</x-livewind::modal>');

    expect($html)
        ->toContain('show: true')
        ->toContain('max-w-2xl')
        ->toContain('Editar');
});

it('omits close controls when not closeable', function () {
    $html = Blade::render('<x-livewind::modal name="locked" :closeable="false">Bloqueado</x-livewind::modal>');

    expect($html)
        ->not->toContain('x-on:keydown.escape.window="close()"')
        ->not->toContain('aria-label="Fechar modal"')
        ->not->toContain('x-on:click="close()"');
});

it('merges consumer classes on the panel', function () {
    $html = Blade::render('<x-livewind::modal name="custom" class="divide-y" data-test="modal">Conteudo</x-livewind::modal>');

    expect($html)
        ->toContain('divide-y')
        ->toContain('data-test="modal"')
        ->toContain('rounded-lg bg-surface text-surface-foreground shadow-xl');
});

it('falls back to the configured default max width', function () {
    config(['livewind.modal.max_width' => 'xl']);

    $html = Blade::render('<x-livewind::modal name="cfg">Conteudo</x-livewind::modal>');

    expect($html)->toContain('max-w-xl');
});

it('dispatches modal open events from livewire', function () {
    Livewire::test(SprintTwoModalDispatcher::class)
        ->call('openConfirm')
        ->assertDispatched('livewind-modal-open');
});

class SprintTwoModalDispatcher extends Component
{
    public function openConfirm(): void
    {
        $this->dispatch('livewind-modal-open', name: 'confirm-delete');
    }

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-livewind::button wire:click="openConfirm">Abrir</x-livewind::button>
                <x-livewind::modal name="confirm-delete">
                    <div class="p-6">
                        <h2 id="modal-title-confirm-delete">Confirmar exclusão?</h2>
                    </div>
                </x-livewind::modal>
            </div>
        BLADE;
    }
}
