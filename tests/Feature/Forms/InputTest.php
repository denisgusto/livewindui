<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Livewire\Livewire;

it('renders with label, hint and wire model', function () {
    $html = Blade::render('<x-livewindui::input model="email" label="E-mail" hint="Use seu melhor e-mail." placeholder="voce@site.com" />');

    expect($html)
        ->toContain('<label')
        ->toContain('E-mail')
        ->toContain('Use seu melhor e-mail.')
        ->toContain('wire:model="email"')
        ->toContain('placeholder="voce@site.com"')
        ->toContain('aria-invalid="false"');
});

it('supports live model binding', function () {
    $html = Blade::render('<x-livewindui::input model="name" :model-live="true" />');

    expect($html)
        ->toContain('wire:model.live="name"')
        ->not->toContain('wire:model="name"');
});

it('renders prefix and suffix slots', function () {
    $html = Blade::render(<<<'BLADE'
        <x-livewindui::input model="amount" label="Valor">
            <x-slot:prefix>R$</x-slot:prefix>
            <x-slot:suffix>BRL</x-slot:suffix>
        </x-livewindui::input>
    BLADE);

    expect($html)
        ->toContain('R$')
        ->toContain('BRL')
        ->toContain('pl-10')
        ->toContain('pr-10');
});

it('merges consumer classes and arbitrary attributes', function () {
    $html = Blade::render('<x-livewindui::input model="email" class="tracking-wide" data-test="input" />');

    expect($html)
        ->toContain('tracking-wide')
        ->toContain('border-gray-300')
        ->toContain('data-test="input"');
});

it('binds wire model in a livewire component', function () {
    Livewire::test(SprintOneInputForm::class)
        ->assertSee('wire:model="email"', escape: false)
        ->set('email', 'denis@example.com')
        ->assertSet('email', 'denis@example.com');
});

it('shows validation errors from a livewire component', function () {
    Livewire::test(SprintOneInputForm::class)
        ->set('email', 'invalid')
        ->call('save')
        ->assertHasErrors(['email' => 'email'])
        ->assertSee('The email field must be a valid email address.');
});

class SprintOneInputForm extends Component
{
    public string $email = '';

    public function rules(): array
    {
        return ['email' => 'required|email'];
    }

    public function save(): void
    {
        $this->validate();
    }

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-livewindui::input model="email" label="E-mail" hint="Informe seu e-mail." />
                <x-livewindui::button wire:click="save" loading="save">Salvar</x-livewindui::button>
            </div>
        BLADE;
    }
}
