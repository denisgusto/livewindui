# SPRINT-03 — Dados e navegação

**Duração estimada:** 2-3 semanas
**Pré-requisito:** SPRINT-02 concluído (formulários + feedback + modal + toast funcionando).
**Iteração do Quadro 5 do TCC:** 3

---

## Objetivo

Implementar os componentes mais complexos da biblioteca — **DataTable**, **Tabs**, **Dropdown**, **Pagination** — que demonstram composição interna (DataTable usa Input/Select/Pagination internamente) e dominam o desafio técnico mais alto do projeto: ordenação, busca com debounce e paginação reativas via Livewire em um único componente coeso.

Ao final, a página `/contatos` da demo deve ter uma tabela completa de produção, com performance comparável a soluções nativas de DataTables JS, mas usando apenas Livewire.

---

## Entregáveis

### 1. Componente Pagination

`resources/views/components/pagination.blade.php` (anônimo).

Props:
- `paginator`: instância `LengthAwarePaginator` ou `Paginator` (passada pelo componente Livewire pai).
- `compact`: bool — versão compacta (só anterior/próximo + indicador) — default false.

Comportamento:
- Renderiza navegação com botões numerados (ou compact com setas).
- Usa as URLs/onclick do paginator do Livewire — paginação reativa automática.
- ARIA: `nav role="navigation" aria-label="Paginação"`.
- Estado disabled em primeira/última página.
- Indicador de "X-Y de Z resultados".

### 2. Componente Table (básico)

`resources/views/components/table.blade.php` (anônimo).

Props mínimas:
- `striped`: bool default false.
- `hover`: bool default true.
- `compact`: bool default false.

Slots:
- Slot principal recebe `<x-livewindui::table-row>` e estes recebem `<x-livewindui::table-cell>`.

Implementação:
- Renderiza `<table>` + `<thead>` (via slot `head`) + `<tbody>` (slot principal).
- Componentes auxiliares: `table-row`, `table-cell`, `table-header` — todos anônimos com classes Tailwind por variant.

### 3. Componente DataTable — o componente mais complexo

Este componente **tem classe PHP** em `src/Components/Data/DataTable.php` por causa da lógica de configuração.

Props:
- `columns`: array de definições — `[['key' => 'name', 'label' => 'Nome', 'sortable' => true, 'searchable' => true], ...]`.
- `rows`: paginator vindo do componente Livewire pai.
- `search`: string (entangled com a prop de busca do pai).
- `sortBy`: string (entangled).
- `sortDirection`: 'asc' | 'desc' (entangled).
- `searchable`: bool — default true.
- `searchPlaceholder`: default 'Buscar...'.
- `searchDebounce`: int (ms) — default 300.
- `emptyMessage`: default 'Nenhum resultado encontrado.'.

Slots nomeados:
- `header`: conteúdo extra na barra de cabeçalho (ex: botão "Novo").
- `filters`: filtros customizados (Selects) ao lado da busca.
- `cell-<key>`: customização por célula da coluna `<key>` — recebe `$row` no escopo.
- `actions`: coluna final de ações por linha — recebe `$row`.

Comportamento:
- Barra de cabeçalho: busca (Input) à esquerda, slot `filters` no meio, slot `header` à direita.
- Tabela: cabeçalhos clicáveis para ordenação (alterna asc/desc, mostra seta de direção).
- Click no cabeçalho dispara método Livewire `sortBy($key)` no componente pai (assumido).
- Busca tem debounce de 300ms via `wire:model.live.debounce.300ms`.
- Estado vazio com mensagem.
- Loading state: durante requisições Livewire, overlay sutil sobre a tabela (via `wire:loading.delay`).
- Paginação no rodapé.

Padrão de uso pelo componente Livewire pai:

```php
class ContatosIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

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

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        return view('livewire.contatos.index', [
            'rows' => Contato::query()
                ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate(10),
        ]);
    }
}
```

```blade
<x-livewindui::data-table
    :columns="[
        ['key' => 'name', 'label' => 'Nome', 'sortable' => true],
        ['key' => 'company', 'label' => 'Empresa', 'sortable' => true],
        ['key' => 'email', 'label' => 'E-mail'],
        ['key' => 'active', 'label' => 'Ativo'],
    ]"
    :rows="$rows"
>
    <x-slot:header>
        <x-livewindui::button wire:click="openCreate">+ Novo contato</x-livewindui::button>
    </x-slot:header>

    @scope('cell-active', $row)
        <x-livewindui::toggle :model="'rows.'.$row->id.'.active'" />
    @endscope

    @scope('actions', $row)
        <x-livewindui::button size="sm" variant="ghost" wire:click="edit({{ $row->id }})">Editar</x-livewindui::button>
        <x-livewindui::button size="sm" variant="danger" wire:click="askDelete({{ $row->id }})">Excluir</x-livewindui::button>
    @endscope
</x-livewindui::data-table>
```

### 4. Componente Tabs

`resources/views/components/tabs.blade.php` (anônimo).

Props:
- `defaultTab`: string — qual aba começa ativa.
- `serverSide`: bool — se true, sincroniza com Livewire via @entangle.live (para lazy load); se false, só Alpine.

Estrutura:
- `<x-livewindui::tabs>` wrapper.
- `<x-livewindui::tab-list>` lista de botões de aba.
- `<x-livewindui::tab name="...">` botão individual.
- `<x-livewindui::tab-panels>` container de painéis.
- `<x-livewindui::tab-panel name="...">` conteúdo de cada aba.

Comportamento:
- Alpine.js: estado `active` controla qual aba está aberta.
- Quando `serverSide=true`: `@entangle('activeTab').live`.
- ARIA: `role="tablist"`, `role="tab"`, `role="tabpanel"`, `aria-selected`, `aria-controls`, `aria-labelledby`.
- Navegação por teclado: setas esquerda/direita alternam abas.

### 5. Componente Dropdown

Ver `.claude/skills/setup-livewire-entangle.md` §4 — só Alpine, sem @entangle.

`resources/views/components/dropdown.blade.php` (anônimo).

Props:
- `align`: `left` | `right` | `center` — default `right`.
- `width`: `sm` | `md` | `lg` | `auto` — default `md`.

Slots:
- `trigger`: o elemento clicável.
- Slot principal: conteúdo do menu (geralmente `<x-livewindui::dropdown-item>`).

Componente auxiliar `dropdown-item` (anônimo):
- Renderiza como `<button>` ou `<a>` dependendo se tem `href`.
- ARIA `role="menuitem"`.

### 6. Testes Pest

- [ ] `PaginationTest.php` — renderização com paginator, estado disabled em primeira página.
- [ ] `TableTest.php` — slots `head` + linhas + células.
- [ ] `DataTableTest.php` — caso mais complexo: testar via componente Livewire real com dataset Eloquent in-memory:
  - Click em cabeçalho ordena.
  - Buscar filtra.
  - Paginação navega.
  - Slot `cell-<key>` customiza coluna.
  - Slot `actions` aparece na última coluna.
- [ ] `TabsTest.php` — navegação por click, navegação por teclado (snapshot keydown.arrow-right), serverSide=true sincroniza com Livewire.
- [ ] `DropdownTest.php` — abertura via click, fechamento ESC e click-outside, ARIA correto.

### 7. Demo

- [ ] `/components/pagination`, `/components/table`, `/components/data-table`, `/components/tabs`, `/components/dropdown`.
- [ ] **Refatorar `/contatos`** para usar DataTable real com:
  - Migração + factory Eloquent para gerar 50+ contatos fake (Faker).
  - Busca + ordenação + paginação reativas.
  - Toggle inline na coluna "Ativo" (alteração persistida).
  - Modal de criar/editar com Form completo (do Sprint 2).
  - Dropdown de ações por linha (Editar/Excluir/Duplicar).
  - Tabs no formulário do contato: "Dados gerais", "Endereço", "Observações".

---

## Critérios de aceitação

1. DataTable com 50 contatos: busca por nome com debounce funciona, ordenação clicável funciona, paginação funciona, **sem reload de página**.
2. Performance: tempo de resposta de busca/ordenação na demo local com 50 registros < 200ms (medir aba Network do navegador).
3. Tabs no formulário de contato preservam o estado ao navegar entre abas.
4. Dropdown fecha ao clicar fora ou pressionar ESC.
5. Toggle na coluna "Ativo" atualiza o registro no banco e dispara Toast de sucesso, tudo sem reload.
6. `vendor/bin/pest` 100% verde. Cobertura ≥ 70%.
7. axe-core /contatos sem violações críticas.
8. Build da demo continua sem JS próprio.

---

## Sequência sugerida

1. Pagination → Table → DataTable (do mais simples para o mais complexo).
2. Para DataTable: comece com renderização básica (sem busca/sort), depois adicione busca, depois sort, depois slots customizados. Teste a cada etapa.
3. Tabs → Dropdown.
4. Refatoração final da `/contatos`.

---

## Notas e armadilhas

- **DataTable é o componente onde a complexidade dobra.** Não tente fazer tudo numa tacada. Implemente em camadas (renderização básica primeiro, depois sort, depois busca, depois slots).
- O slot `cell-<key>` usa `@scope` do Laravel Blade — sintaxe relativamente nova, confirme que está habilitada.
- Busca com debounce: usar `wire:model.live.debounce.300ms` no Input interno. O componente pai não precisa configurar nada.
- Tabs `serverSide=true` tem custo de request por troca de aba — só use quando o conteúdo é pesado.
- Dropdown: cuidado com `click.outside` aninhado em dropdowns dentro de outros dropdowns. Use `x-on:click.outside.stop`.

---

## Saída de revisão

- [ ] Componentes implementados: 5 (Pagination, Table, DataTable, Tabs, Dropdown) + 3 auxiliares (table-row, table-cell, table-header, dropdown-item).
- [ ] Testes Pest: ___ . Cobertura: ___%.
- [ ] axe-core /contatos: ___ críticas.
- [ ] Bundle JS: ___ KB.
- [ ] DataTable performance medida na demo (50 reg): ___ms busca / ___ms sort / ___ms página.

**Próximo sprint:** `SPRINT-04-demo-tests-docs.md`.
