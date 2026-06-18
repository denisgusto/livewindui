<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use LiveWindUi\Concerns\InteractsWithToasts;
use Livewire\Component;
use Livewire\Livewire;

it('renders the toast container with window listeners', function () {
    $html = Blade::render('<x-livewindui::toast />');

    expect($html)
        ->toContain('x-data="{')
        ->toContain("window.addEventListener('livewindui:toast.show'")
        ->toContain("window.addEventListener('livewindui:toast'")
        ->toContain('window.LiveWindUI.toast')
        ->toContain('x-for="toast in toasts"')
        ->toContain('x-transition');
});

it('does not use the broken alpine dotted-event listener', function () {
    $html = Blade::render('<x-livewindui::toast />');

    expect($html)
        ->not->toContain('x-on:livewindui:toast.show.window')
        ->not->toContain('x-on:livewire:init.window');
});

it('applies position and default duration props', function () {
    $html = Blade::render('<x-livewindui::toast position="bottom-left" :duration="2500" />');

    expect($html)
        ->toContain('bottom-0 left-0 items-start')
        ->toContain('defaultDuration: 2500');
});

it('normalizes the supported payload shapes in the add method', function () {
    $html = Blade::render('<x-livewindui::toast />');

    expect($html)
        // string simples -> { message: raw }
        ->toContain("typeof raw === 'string' ? { message: raw }")
        // { message, title } e aliases { text, heading }
        ->toContain('detail.title ?? detail.heading')
        ->toContain('detail.message ?? detail.text')
        // variant com default
        ->toContain("detail.variant ?? 'info'")
        // duration por toast com fallback no default
        ->toContain('detail.duration !== undefined');
});

it('treats duration zero as a permanent toast', function () {
    $html = Blade::render('<x-livewindui::toast />');

    expect($html)
        ->toContain('if (! toast.duration || toast.duration <= 0) return;');
});

it('falls back to config defaults for position, duration and max', function () {
    config([
        'livewindui.toast.position' => 'bottom-left',
        'livewindui.toast.duration' => 7000,
        'livewindui.toast.max' => 2,
    ]);

    $html = Blade::render('<x-livewindui::toast />');

    expect($html)
        ->toContain('bottom-0 left-0 items-start')
        ->toContain('defaultDuration: 7000')
        ->toContain('max: 2');
});

it('dedupes identical toasts and caps the stack', function () {
    $html = Blade::render('<x-livewindui::toast :max="3" />');

    expect($html)
        ->toContain('max: 3')
        ->toContain('const duplicate = this.toasts.find')
        ->toContain('while (this.toasts.length > this.max)');
});

it('seeds session-flashed toasts into the container', function () {
    app('livewind')->toast(message: 'Apos redirect', variant: 'info');

    $html = Blade::render('<x-livewindui::toast />');

    expect($html)
        ->toContain('Apos redirect')
        ->toContain('.forEach((flashed) => add(flashed))');
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

it('makes the standalone toast-item dismissible by default', function () {
    $html = Blade::render('<x-livewindui::toast-item message="Fechavel" />');

    expect($html)
        ->toContain('x-data="{ show: true }"')
        ->toContain('x-on:click="show = false"');
});

it('omits the dismiss button when not dismissible', function () {
    $html = Blade::render('<x-livewindui::toast-item message="Fixo" :dismissible="false" />');

    expect($html)
        ->not->toContain('x-on:click="show = false"')
        ->not->toContain('aria-label="Fechar notificacao"');
});

it('dispatches the legacy toast event from livewire', function () {
    Livewire::test(SprintTwoToastDispatcher::class)
        ->call('notifyLegacy')
        ->assertDispatched('livewindui:toast.show');
});

it('dispatches the new toast event through the trait', function () {
    Livewire::test(SprintTwoToastDispatcher::class)
        ->call('notify')
        ->assertDispatched('livewindui:toast');
});

it('passes message, title, variant and duration through the trait', function () {
    Livewire::test(SprintTwoToastDispatcher::class)
        ->call('notifyDetailed')
        ->assertDispatched(
            'livewindui:toast',
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
