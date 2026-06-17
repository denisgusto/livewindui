<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Livewire\Livewire;

it('renders modal shell with event listeners and aria attributes', function () {
    $html = Blade::render(<<<'BLADE'
        <x-livewindui::modal name="confirm-delete" max-width="sm">
            <div class="p-6">
                <h2 id="modal-title-confirm-delete">Confirmar exclusão?</h2>
            </div>
        </x-livewindui::modal>
    BLADE);

    expect($html)
        ->toContain('role="dialog"')
        ->toContain('aria-modal="true"')
        ->toContain('aria-labelledby="modal-title-confirm-delete"')
        ->toContain("x-on:livewindui-modal-open.window=\"if (\$event.detail.name === 'confirm-delete') open()\"")
        ->toContain("x-on:livewindui-modal-close.window=\"if (\$event.detail.name === 'confirm-delete') close()\"")
        ->toContain('x-on:keydown.escape.window="close()"')
        ->toContain('x-trap.noscroll="show"')
        ->toContain('max-w-sm');
});

it('supports initial show state and custom max width', function () {
    $html = Blade::render('<x-livewindui::modal name="edit" max-width="2xl" :show="true">Editar</x-livewindui::modal>');

    expect($html)
        ->toContain('show: true')
        ->toContain('max-w-2xl')
        ->toContain('Editar');
});

it('omits close controls when not closeable', function () {
    $html = Blade::render('<x-livewindui::modal name="locked" :closeable="false">Bloqueado</x-livewindui::modal>');

    expect($html)
        ->not->toContain('x-on:keydown.escape.window="close()"')
        ->not->toContain('aria-label="Fechar modal"')
        ->not->toContain('x-on:click="close()"');
});

it('merges consumer classes on the panel', function () {
    $html = Blade::render('<x-livewindui::modal name="custom" class="divide-y" data-test="modal">Conteudo</x-livewindui::modal>');

    expect($html)
        ->toContain('divide-y')
        ->toContain('data-test="modal"')
        ->toContain('rounded-lg bg-white shadow-xl');
});

it('dispatches modal open events from livewire', function () {
    Livewire::test(SprintTwoModalDispatcher::class)
        ->call('openConfirm')
        ->assertDispatched('livewindui-modal-open');
});

class SprintTwoModalDispatcher extends Component
{
    public function openConfirm(): void
    {
        $this->dispatch('livewindui-modal-open', name: 'confirm-delete');
    }

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-livewindui::button wire:click="openConfirm">Abrir</x-livewindui::button>
                <x-livewindui::modal name="confirm-delete">
                    <div class="p-6">
                        <h2 id="modal-title-confirm-delete">Confirmar exclusão?</h2>
                    </div>
                </x-livewindui::modal>
            </div>
        BLADE;
    }
}
