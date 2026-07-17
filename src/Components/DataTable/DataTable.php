<?php

declare(strict_types=1);

namespace Livewind\Components\DataTable;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\View\View;
use Livewire\Component as LivewireComponent;

/**
 * Renderiza uma tabela de dados reativa preparada para Livewire.
 *
 * O componente recebe colunas normalizadas, linhas em array/Collection/Paginator,
 * nomes de propriedades Livewire para busca e ordenacao, e partials opcionais
 * para customizar celulas e acoes por linha.
 */
final class DataTable extends Component
{
    /**
     * @param  array<int, array<string, mixed>>  $columns
     * @param  array<string, string>  $cellViews
     */
    public function __construct(
        public readonly array $columns = [],
        public readonly mixed $rows = [],
        public readonly string $search = 'search',
        public readonly string $sortBy = 'sortBy',
        public readonly string $sortDirection = 'sortDirection',
        public readonly bool $searchable = true,
        public readonly ?string $searchPlaceholder = null,
        public readonly int $searchDebounce = 300,
        public readonly ?string $emptyMessage = null,
        public readonly array $cellViews = [],
        public readonly ?string $actionsView = null,
    ) {}

    /**
     * Resolve a view do componente com helpers prontos para o template.
     */
    public function render(): View
    {
        return view('livewind::data-table', [
            'normalizedColumns' => $this->normalizedColumns(),
            'rowValue' => fn (mixed $row, string $key): mixed => $this->value($row, $key),
            'rowKey' => fn (mixed $row, int $index): string => $this->rowKey($row, $index),
            'isPaginator' => $this->isPaginator(),
            // Chaves propositalmente distintas dos nomes de metodo publico
            // (currentSort/currentSortDirection), que o Laravel tambem expoe como
            // variaveis na view e sombreariam estes valores.
            'activeSortKey' => $this->currentSort(),
            'activeSortDir' => $this->currentSortDirection(),
        ]);
    }

    /**
     * Coluna atualmente ordenada, lida da propriedade Livewire cujo nome foi
     * informado em `$sortBy` (ex.: 'sortBy'). Retorna null fora de um contexto
     * Livewire ou quando a propriedade nao existe.
     */
    public function currentSort(): ?string
    {
        $value = $this->livewireProperty($this->sortBy);

        return $value === null ? null : (string) $value;
    }

    /**
     * Direcao atual de ordenacao ('asc'|'desc'), lida da propriedade Livewire
     * cujo nome foi informado em `$sortDirection`. Default 'asc'.
     */
    public function currentSortDirection(): string
    {
        return (string) ($this->livewireProperty($this->sortDirection) ?? 'asc');
    }

    /**
     * Le o valor de uma propriedade do componente Livewire em execucao pelo nome.
     */
    private function livewireProperty(string $name): mixed
    {
        $component = app('livewire')->current();

        if ($component instanceof LivewireComponent) {
            return data_get($component, $name);
        }

        return null;
    }

    /**
     * Normaliza definicoes de colunas para uma estrutura previsivel no Blade.
     *
     * @return array<int, array{key: string, label: string, sortable: bool, searchable: bool}>
     */
    public function normalizedColumns(): array
    {
        return array_values(array_map(
            fn (array $column): array => [
                'key' => (string) ($column['key'] ?? ''),
                'label' => (string) ($column['label'] ?? Str::headline((string) ($column['key'] ?? ''))),
                'sortable' => (bool) ($column['sortable'] ?? false),
                'searchable' => (bool) ($column['searchable'] ?? false),
            ],
            $this->columns
        ));
    }

    /**
     * Extrai um valor de linha por chave, suportando arrays e objetos.
     */
    public function value(mixed $row, string $key): mixed
    {
        if (is_array($row)) {
            return Arr::get($row, $key);
        }

        if (is_object($row)) {
            return data_get($row, $key);
        }

        return null;
    }

    /**
     * Gera uma chave estavel para o wire:key da linha.
     */
    public function rowKey(mixed $row, int $index): string
    {
        $id = $this->value($row, 'id') ?? $index;

        return 'livewind-row-'.$id;
    }

    /**
     * Indica se as linhas vieram de um paginator Laravel/Livewire.
     */
    public function isPaginator(): bool
    {
        return $this->rows instanceof Paginator;
    }
}
