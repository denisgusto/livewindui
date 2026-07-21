<div class="space-y-4">
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1">
            <label for="busca" class="sr-only">Buscar</label>
            <input
                id="busca"
                type="search"
                wire:model.live.debounce.300ms="busca"
                placeholder="Buscar produtos..."
                class="block w-full rounded-md border border-border bg-surface px-3 py-2 text-sm text-surface-foreground shadow-sm focus:outline-none focus:ring-2 focus:ring-accent"
            />
        </div>
        <div>
            <label for="categoria" class="sr-only">Categoria</label>
            <select
                id="categoria"
                wire:model.live="categoria"
                class="rounded-md border border-border bg-surface px-3 py-2 text-sm text-surface-foreground shadow-sm focus:outline-none focus:ring-2 focus:ring-accent"
            >
                <option value="">Todas as categorias</option>
                @foreach ($categorias as $c)
                    <option value="{{ $c->id }}">{{ $c->nome }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-border">
        <table class="w-full text-left text-sm">
            <thead class="bg-muted text-muted-foreground">
                <tr>
                    @foreach (['nome' => 'Produto', 'preco' => 'Preço', 'estoque' => 'Estoque'] as $campo => $rotulo)
                        <th scope="col" class="px-4 py-3 font-medium">
                            <button
                                type="button"
                                wire:click="ordenarPor('{{ $campo }}')"
                                class="inline-flex items-center gap-1 hover:text-surface-foreground"
                                aria-sort="{{ $ordenarPor === $campo ? ($direcao === 'asc' ? 'ascending' : 'descending') : 'none' }}"
                            >
                                {{ $rotulo }}
                                @if ($ordenarPor === $campo)
                                    <span aria-hidden="true">{{ $direcao === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </button>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($produtos as $produto)
                    <tr wire:key="produto-{{ $produto->id }}" class="hover:bg-muted/50">
                        <td class="px-4 py-3 text-surface-foreground">{{ $produto->nome }}</td>
                        <td class="px-4 py-3 text-surface-foreground">{{ $produto->preco }}</td>
                        <td class="px-4 py-3 text-surface-foreground">{{ $produto->estoque }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-muted-foreground">
                            Nenhum produto encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div wire:loading.class="opacity-50" wire:target="busca,categoria,ordenarPor">
        {{ $produtos->links() }}
    </div>
</div>
