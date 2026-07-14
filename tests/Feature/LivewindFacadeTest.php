<?php

declare(strict_types=1);

use Livewind\Facades\Livewind;
use Livewind\LivewindManager;
use Livewire\Component;
use Livewire\Livewire;

it('resolves the manager from the container and facade', function () {
    expect(app('livewind'))->toBeInstanceOf(LivewindManager::class)
        ->and(app(LivewindManager::class))->toBeInstanceOf(LivewindManager::class)
        ->and(Livewind::getFacadeRoot())->toBeInstanceOf(LivewindManager::class);
});

it('normalizes toast payloads with flux aliases', function () {
    expect(LivewindManager::normalizeToast(text: 'Corpo', heading: 'Titulo', variant: 'success'))
        ->toBe([
            'variant' => 'success',
            'title' => 'Titulo',
            'message' => 'Corpo',
        ]);
});

it('keeps duration zero as a permanent toast in the payload', function () {
    expect(LivewindManager::normalizeToast(message: 'Fixo', duration: 0))
        ->toHaveKey('duration', 0);
});

it('dispatches a toast through the facade on the current component', function () {
    Livewire::test(LivewindFacadeHarness::class)
        ->call('fireToast')
        ->assertDispatched(
            'livewind:toast',
            variant: 'success',
            title: 'Pronto',
            message: 'Salvo via facade',
        );
});

it('opens a named modal through the facade', function () {
    Livewire::test(LivewindFacadeHarness::class)
        ->call('openModal')
        ->assertDispatched('livewind-modal-open', name: 'confirm');
});

it('closes all modals through the facade without a name', function () {
    Livewire::test(LivewindFacadeHarness::class)
        ->call('closeAllModals')
        ->assertDispatched('livewind-modal-close');
});

it('dispatches variant helpers with the matching variant', function (string $method, string $variant) {
    Livewire::test(LivewindFacadeHarness::class)
        ->call('fireVariant', $method)
        ->assertDispatched('livewind:toast', variant: $variant);
})->with([
    'success' => ['success', 'success'],
    'info' => ['info', 'info'],
    'warning' => ['warning', 'warning'],
    'danger' => ['danger', 'danger'],
    'error maps to danger' => ['error', 'danger'],
]);

it('flashes toasts to the session when no livewire component is active', function () {
    expect(app('livewire')->current())->toBeEmpty();

    Livewind::toast(message: 'Apos redirect', title: 'Ola', variant: 'success');

    expect(Livewind::flashedToasts())->toBe([
        ['variant' => 'success', 'title' => 'Ola', 'message' => 'Apos redirect'],
    ]);
});

it('accumulates multiple flashed toasts', function () {
    Livewind::success('Primeiro');
    Livewind::error('Segundo');

    expect(Livewind::flashedToasts())->toHaveCount(2)
        ->and(Livewind::flashedToasts()[1]['variant'])->toBe('danger');
});

it('registers the global Livewind facade alias', function () {
    expect(class_exists('Livewind'))->toBeTrue()
        ->and(\Livewind::getFacadeRoot())->toBeInstanceOf(LivewindManager::class);
});

class LivewindFacadeHarness extends Component
{
    public function fireToast(): void
    {
        Livewind::toast(message: 'Salvo via facade', title: 'Pronto', variant: 'success');
    }

    public function fireVariant(string $method): void
    {
        Livewind::{$method}('Mensagem '.$method);
    }

    public function openModal(): void
    {
        Livewind::modal('confirm')->show();
    }

    public function closeAllModals(): void
    {
        Livewind::modals()->close();
    }

    public function render(): string
    {
        return '<div></div>';
    }
}
