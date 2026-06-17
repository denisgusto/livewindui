<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Livewire\Livewire;

it('renders the toast container with livewire listener', function () {
    $html = Blade::render('<x-livewindui::toast />');

    expect($html)
        ->toContain('x-data="{')
        ->toContain("Livewire.on('livewindui:toast.show'")
        ->toContain('x-on:livewindui:toast.show.window')
        ->toContain('setTimeout(() => this.remove(toast.id), 4000)')
        ->toContain('x-for="toast in toasts"')
        ->toContain('x-transition');
});

it('applies position and duration props', function () {
    $html = Blade::render('<x-livewindui::toast position="bottom-left" :duration="2500" />');

    expect($html)
        ->toContain('bottom-0 left-0 items-start')
        ->toContain('setTimeout(() => this.remove(toast.id), 2500)');
});

it('renders a static toast item with semantic role', function (string $variant, string $expectedClass, string $role) {
    $html = Blade::render("<x-livewindui::toast-item variant=\"{$variant}\" title=\"Titulo\" message=\"Mensagem\" />");

    expect($html)
        ->toContain($expectedClass)
        ->toContain("role=\"{$role}\"")
        ->toContain('Titulo')
        ->toContain('Mensagem');
})->with([
    'success' => ['success', 'bg-green-50', 'status'],
    'info' => ['info', 'bg-blue-50', 'status'],
    'warning' => ['warning', 'bg-yellow-50', 'alert'],
    'danger' => ['danger', 'bg-red-50', 'alert'],
]);

it('dispatches the toast event from livewire', function () {
    Livewire::test(SprintTwoToastDispatcher::class)
        ->call('notify')
        ->assertDispatched('livewindui:toast.show');
});

class SprintTwoToastDispatcher extends Component
{
    public function notify(): void
    {
        $this->dispatch(
            'livewindui:toast.show',
            variant: 'success',
            message: 'Contato salvo com sucesso!',
            title: 'Sucesso',
        );
    }

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-livewindui::toast />
                <x-livewindui::button wire:click="notify">Notificar</x-livewindui::button>
            </div>
        BLADE;
    }
}
