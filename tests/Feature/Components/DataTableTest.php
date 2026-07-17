<?php

declare(strict_types=1);

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Livewire\Component;
use Livewire\Livewire;
use Livewire\WithPagination;

class DataTableFixture extends Component
{
    use WithPagination;

    public string $search = '';

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    /**
     * @var array<int, array{id: int, name: string, company: string, email: string, active: string}>
     */
    public array $records = [
        ['id' => 1, 'name' => 'Ana Martins', 'company' => 'Norte Digital', 'email' => 'ana@example.com', 'active' => 'Sim'],
        ['id' => 2, 'name' => 'Bruno Costa', 'company' => 'Atlas Labs', 'email' => 'bruno@example.com', 'active' => 'Nao'],
        ['id' => 3, 'name' => 'Carla Rocha', 'company' => 'Viva Health', 'email' => 'carla@example.com', 'active' => 'Sim'],
    ];

    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): string
    {
        $rows = collect($this->records)
            ->filter(fn (array $row): bool => $this->search === '' || str_contains(strtolower($row['name'].' '.$row['company'].' '.$row['email']), strtolower($this->search)))
            ->sortBy($this->sortBy, SORT_REGULAR, $this->sortDirection === 'desc')
            ->values();

        $paginator = new LengthAwarePaginator(
            items: $rows->forPage($this->getPage(), 2)->values(),
            total: $rows->count(),
            perPage: 2,
            currentPage: $this->getPage(),
            options: ['pageName' => 'page']
        );

        return Blade::render(<<<'BLADE'
            <div>
                <x-livewind::data-table
                    :columns="[
                        ['key' => 'name', 'label' => 'Nome', 'sortable' => true],
                        ['key' => 'company', 'label' => 'Empresa', 'sortable' => true],
                        ['key' => 'email', 'label' => 'E-mail'],
                        ['key' => 'active', 'label' => 'Ativo'],
                    ]"
                    :rows="$paginator"
                    empty-message="Sem registros"
                >
                    <x-slot:header>
                        <span>Novo contato</span>
                    </x-slot:header>

                    <x-slot:actions>
                        <span>Editar</span>
                    </x-slot:actions>
                </x-livewind::data-table>
            </div>
        BLADE, [
            'paginator' => $paginator,
        ]);
    }
}

it('renders columns rows search and action slot', function () {
    Livewire::test(DataTableFixture::class)
        ->assertSee('Search')
        ->assertSee('Nome')
        ->assertSee('Ana Martins')
        ->assertSee('Bruno Costa')
        ->assertSee('Novo contato')
        ->assertSee('Editar')
        ->assertSee('wire:model.live.debounce.300ms="search"', escape: false);
});

it('filters rows by search', function () {
    Livewire::test(DataTableFixture::class)
        ->set('search', 'carla')
        ->assertSee('Carla Rocha')
        ->assertDontSee('Ana Martins')
        ->assertDontSee('Bruno Costa');
});

it('sorts rows using parent livewire method', function () {
    Livewire::test(DataTableFixture::class)
        ->call('sortBy', 'name')
        ->assertSet('sortDirection', 'desc')
        ->assertSeeInOrder(['Carla Rocha', 'Bruno Costa']);
});

it('shows the sort indicator for the current sorted column', function () {
    // Le o valor real da propriedade Livewire ($sortBy/$sortDirection) via
    // app('livewire')->current(), entao o indicador reflete o estado atual.
    $component = Livewire::test(DataTableFixture::class);

    // Estado inicial: sortBy='name', asc -> seta para cima e nao para baixo.
    expect($component->html())
        ->toContain('↑')
        ->not->toContain('↓');

    // Reordena a mesma coluna -> direcao vira desc -> seta para baixo.
    $component->call('sortBy', 'name');

    expect($component->html())
        ->toContain('↓')
        ->not->toContain('↑');
});

it('paginates rows through livewire', function () {
    Livewire::test(DataTableFixture::class)
        ->assertSee('Ana Martins')
        ->assertDontSee('Carla Rocha')
        ->call('gotoPage', 2)
        ->assertSee('Carla Rocha');
});

it('renders row scoped cell and action partials', function () {
    View::addNamespace('livewind-tests', __DIR__.'/../../Fixtures/views');

    $html = Blade::render(<<<'BLADE'
        <x-livewind::data-table
            :columns="$columns"
            :rows="$rows"
            :cell-views="['active' => 'livewind-tests::data-table.active-cell']"
            actions-view="livewind-tests::data-table.actions-cell"
            :searchable="false"
        />
    BLADE, [
        'columns' => [
            ['key' => 'name', 'label' => 'Nome'],
            ['key' => 'active', 'label' => 'Ativo'],
        ],
        'rows' => [
            ['id' => 1, 'name' => 'Ana Martins', 'active' => 'Sim'],
        ],
    ]);

    expect($html)
        ->toContain('Ana Martins')
        ->toContain('ativo-1-Sim')
        ->toContain('acoes-1');
});
