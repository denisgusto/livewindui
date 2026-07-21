<x-livewind::data-table
    :rows="$produtos"
    :columns="[
        ['key' => 'nome', 'label' => 'Produto', 'sortable' => true],
        ['key' => 'preco', 'label' => 'Preço', 'sortable' => true],
        ['key' => 'estoque', 'label' => 'Estoque', 'sortable' => true],
    ]"
    search="busca"
    empty-message="Nenhum produto encontrado."
>
    <x-slot:filters>
        <x-livewind::select model-live="categoria" :options="$categorias" placeholder="Todas as categorias" />
    </x-slot:filters>
</x-livewind::data-table>
