<?php

declare(strict_types=1);

namespace LiveWindUi\Components\Data;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\View\View;

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
        public readonly string $searchPlaceholder = 'Buscar...',
        public readonly int $searchDebounce = 300,
        public readonly string $emptyMessage = 'Nenhum resultado encontrado.',
        public readonly array $cellViews = [],
        public readonly ?string $actionsView = null,
    ) {}

    /**
     * Resolve a view do componente com helpers prontos para o template.
     */
    public function render(): View
    {
        return view('livewindui::components.data-table', [
            'normalizedColumns' => $this->normalizedColumns(),
            'rowValue' => fn (mixed $row, string $key): mixed => $this->value($row, $key),
            'rowKey' => fn (mixed $row, int $index): string => $this->rowKey($row, $index),
            'isPaginator' => $this->isPaginator(),
        ]);
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

        return 'livewindui-row-'.$id;
    }

    /**
     * Indica se o conjunto atual possui linhas renderizaveis.
     */
    public function hasRows(): bool
    {
        if ($this->rows instanceof Paginator) {
            return $this->rows->count() > 0;
        }

        return collect($this->rows)->isNotEmpty();
    }

    /**
     * Indica se as linhas vieram de um paginator Laravel/Livewire.
     */
    public function isPaginator(): bool
    {
        return $this->rows instanceof Paginator;
    }
}
