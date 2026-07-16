<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Livewind\Concerns\InteractsWithToasts;
use Livewire\Component;
use Livewire\Livewire;

it('renders the toast container wired to the lwToast bundle component', function () {
    $html = Blade::render('<x-livewind::toast />');

    // A logica agora vive no bundle (Alpine.data lwToast); o Blade so instancia + markup.
    expect($html)
        ->toContain('x-data="lwToast(')
        ->toContain('x-for="toast in toasts"')
        ->toContain('x-transition');
});

it('does not use the broken alpine dotted-event listener', function () {
    $html = Blade::render('<x-livewind::toast />');

    expect($html)
        ->not->toContain('x-on:livewind:toast.show.window')
        ->not->toContain('x-on:livewire:init.window');
});

it('passes position and duration config into lwToast', function () {
    $html = Blade::render('<x-livewind::toast position="bottom-left" :duration="2500" />');

    expect($html)
        ->toContain('bottom-0 left-0 items-start')
        ->toContain('2500');
});

it('falls back to config defaults for position, duration and max', function () {
    config([
        'livewind.toast.position' => 'bottom-left',
        'livewind.toast.duration' => 7000,
        'livewind.toast.max' => 2,
    ]);

    $html = Blade::render('<x-livewind::toast />');

    expect($html)
        ->toContain('bottom-0 left-0 items-start')
        ->toContain('7000');
});

it('seeds session-flashed toasts into the container config', function () {
    app('livewind')->toast(message: 'Apos redirect', variant: 'info');

    $html = Blade::render('<x-livewind::toast />');

    expect($html)->toContain('Apos redirect');
});

it('renders a static toast item with semantic role', function (string $variant, string $expectedClass, string $role) {
    $html = Blade::render("<x-livewind::toast-item variant=\"{$variant}\" title=\"Titulo\" message=\"Mensagem\" />");

    expect($html)
        ->toContain($expectedClass)
        ->toContain("role=\"{$role}\"")
        ->toContain('Titulo')
        ->toContain('Mensagem');
})->with([
    'success' => ['success', 'bg-success/10', 'status'],
    'info' => ['info', 'bg-info/10', 'status'],
    'warning' => ['warning', 'bg-warning/10', 'alert'],
    'danger' => ['danger', 'bg-danger/10', 'alert'],
]);

it('makes the standalone toast-item dismissible by default', function () {
    $html = Blade::render('<x-livewind::toast-item message="Fechavel" />');

    expect($html)
        ->toContain('x-data="{ show: true }"')
        ->toContain('x-on:click="show = false"');
});

it('omits the dismiss button when not dismissible', function () {
    $html = Blade::render('<x-livewind::toast-item message="Fixo" :dismissible="false" />');

    expect($html)
        ->not->toContain('x-on:click="show = false"')
        ->not->toContain('aria-label="Close notification"');
});

it('dispatches the legacy toast event from livewire', function () {
    Livewire::test(SprintTwoToastDispatcher::class)
        ->call('notifyLegacy')
        ->assertDispatched('livewind:toast.show');
});

it('dispatches the new toast event through the trait', function () {
    Livewire::test(SprintTwoToastDispatcher::class)
        ->call('notify')
        ->assertDispatched('livewind:toast');
});

it('passes message, title, variant and duration through the trait', function () {
    Livewire::test(SprintTwoToastDispatcher::class)
        ->call('notifyDetailed')
        ->assertDispatched(
            'livewind:toast',
            variant: 'success',
            title: 'Sucesso',
            message: 'Contato salvo com sucesso!',
            duration: 0,
        );
});

class SprintTwoToastDispatcher extends Component
{
    use InteractsWithToasts;

    public function notify(): void
    {
        $this->toast(message: 'Contato salvo com sucesso!', title: 'Sucesso', variant: 'success');
    }

    public function notifyDetailed(): void
    {
        $this->toast(
            message: 'Contato salvo com sucesso!',
            title: 'Sucesso',
            variant: 'success',
            duration: 0,
        );
    }

    public function notifyLegacy(): void
    {
        $this->dispatch(
            'livewind:toast.show',
            variant: 'success',
            message: 'Contato salvo com sucesso!',
            title: 'Sucesso',
        );
    }

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-livewind::toast />
                <x-livewind::button wire:click="notify">Notificar</x-livewind::button>
            </div>
        BLADE;
    }
}
