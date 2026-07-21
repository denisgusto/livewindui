#!/usr/bin/env python3
"""Gera os 3 cenários de validação em duas versões e conta linhas produtivas.

Critérios de "pronto" (definidos antes da implementação, iguais para as duas versões):
  C1 Formulário  — 8 campos, rótulo, wire:model.live, estado visual de erro,
                   mensagem de erro, atributos ARIA de erro, submit com loading.
  C2 Listagem    — cabeçalhos ordenáveis, busca com debounce, filtro por select,
                   estado vazio, paginação.
  C3 Painel      — 3 cards de métrica, modal acessível (foco/ESC/overlay), toast.
Contagem: linhas não vazias e que não sejam comentário puro.
"""
import os, re

FILES = {}

# ─────────────────────────────────────────────── CENÁRIO 1 — FORMULÁRIO (manual)
campos = [
    ('nome', 'Nome', 'text'), ('email', 'E-mail', 'email'),
    ('telefone', 'Telefone', 'text'), ('empresa', 'Empresa', 'text'),
    ('cargo', 'Cargo', 'text'), ('site', 'Site', 'url'),
    ('cidade', 'Cidade', 'text'), ('cep', 'CEP', 'text'),
]
campo_tpl = '''    <div>
        <label for="{n}" class="mb-1 block text-sm font-medium text-surface-foreground">{l}</label>
        <input
            id="{n}"
            type="{t}"
            wire:model.live="{n}"
            @error('{n}') aria-invalid="true" aria-describedby="{n}-desc" @enderror
            class="block w-full rounded-md border bg-surface px-3 py-2 text-sm text-surface-foreground shadow-sm transition focus:outline-none focus:ring-2 @error('{n}') border-danger focus:ring-danger @else border-border focus:ring-accent @enderror"
        />
        @error('{n}')
            <p id="{n}-desc" class="mt-1 text-sm text-danger">{{{{ $message }}}}</p>
        @enderror
    </div>
'''
c1_manual = '<form wire:submit="salvar" class="space-y-4">\n'
c1_manual += '\n'.join(campo_tpl.format(n=n, l=l, t=t) for n, l, t in campos)
c1_manual += '''
    <button
        type="submit"
        wire:loading.attr="disabled"
        wire:target="salvar"
        class="inline-flex items-center justify-center gap-2 rounded-md bg-accent px-4 py-2 text-sm font-medium text-accent-foreground transition hover:bg-accent-content focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent disabled:opacity-70"
    >
        <span wire:loading wire:target="salvar" class="contents" aria-busy="true">
            <svg class="size-4 shrink-0 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
            </svg>
        </span>
        <span wire:loading.class="opacity-70" wire:target="salvar">Salvar</span>
    </button>
</form>
'''
FILES['c1-manual.blade.php'] = c1_manual

FILES['c1-livewind.blade.php'] = '''<form wire:submit="salvar" class="space-y-4">
''' + '\n'.join(
    f'    <x-livewind::input model="{n}" :model-live="true" label="{l}" type="{t}" />' for n, l, t in campos
) + '''

    <x-livewind::button type="submit" loading="salvar">Salvar</x-livewind::button>
</form>
'''

# ─────────────────────────────────────────────── CENÁRIO 2 — LISTAGEM (manual)
FILES['c2-manual.blade.php'] = '''<div class="space-y-4">
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
'''

FILES['c2-livewind.blade.php'] = '''<x-livewind::data-table
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
'''

# ─────────────────────────────────────────────── CENÁRIO 3 — PAINEL (manual)
FILES['c3-manual.blade.php'] = '''<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-3">
        @foreach ($metricas as $metrica)
            <div class="rounded-lg border border-border bg-surface p-5 shadow-sm">
                <p class="text-sm text-muted-foreground">{{ $metrica['rotulo'] }}</p>
                <p class="mt-1 text-2xl font-semibold text-surface-foreground">{{ $metrica['valor'] }}</p>
            </div>
        @endforeach
    </div>

    <button
        type="button"
        wire:click="abrirDetalhes"
        class="inline-flex items-center justify-center rounded-md bg-accent px-4 py-2 text-sm font-medium text-accent-foreground transition hover:bg-accent-content focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
    >
        Ver detalhes
    </button>

    <div
        x-data="{ aberto: @entangle('mostrarDetalhes') }"
        x-show="aberto"
        x-on:keydown.escape.window="aberto = false"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="titulo-modal"
    >
        <div x-show="aberto" x-transition.opacity x-on:click="aberto = false" class="absolute inset-0 bg-black/50"></div>
        <div
            x-show="aberto"
            x-transition
            x-trap.noscroll="aberto"
            class="relative w-full max-w-md rounded-lg border border-border bg-surface p-6 shadow-lg"
        >
            <h2 id="titulo-modal" class="text-lg font-semibold text-surface-foreground">Detalhes</h2>
            <div class="mt-3 text-sm text-muted-foreground">
                {{ $detalhe }}
            </div>
            <div class="mt-6 flex justify-end">
                <button
                    type="button"
                    x-on:click="aberto = false"
                    class="rounded-md border border-border px-4 py-2 text-sm font-medium text-surface-foreground hover:bg-muted"
                >
                    Fechar
                </button>
            </div>
        </div>
    </div>

    <div
        x-data="{ toasts: [] }"
        x-on:notificar.window="
            const id = Date.now();
            toasts.push({ id, mensagem: $event.detail.mensagem });
            setTimeout(() => toasts = toasts.filter(t => t.id !== id), 4000)
        "
        class="fixed top-4 right-4 z-50 flex w-80 flex-col gap-2"
        role="status"
        aria-live="polite"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition class="rounded-md border border-border bg-surface p-4 text-sm text-surface-foreground shadow-lg">
                <span x-text="toast.mensagem"></span>
            </div>
        </template>
    </div>
</div>
'''

FILES['c3-livewind.blade.php'] = '''<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-3">
        @foreach ($metricas as $metrica)
            <x-livewind::card>
                <x-slot:header>{{ $metrica['rotulo'] }}</x-slot:header>
                {{ $metrica['valor'] }}
            </x-livewind::card>
        @endforeach
    </div>

    <x-livewind::button wire:click="abrirDetalhes">Ver detalhes</x-livewind::button>

    <x-livewind::modal name="detalhes" title="Detalhes">
        {{ $detalhe }}
    </x-livewind::modal>
</div>
'''

# ─────────────────────────────────────────────────────────── escrita e contagem
AQUI = os.path.dirname(os.path.abspath(__file__))
for nome, conteudo in FILES.items():
    open(os.path.join(AQUI, nome), 'w', encoding='utf-8').write(conteudo)

def produtivas(caminho):
    n = 0
    for linha in open(caminho, encoding='utf-8'):
        s = linha.strip()
        if not s:
            continue
        if s.startswith('{{--') and s.endswith('--}}'):
            continue
        n += 1
    return n

print(f"{'Cenário':<28}{'Manual':>8}{'LivewindUI':>12}{'Redução':>10}")
print('-' * 58)
tm = tl = 0
rotulos = {'c1': 'Cenário 1 (Formulário)', 'c2': 'Cenário 2 (Listagem)', 'c3': 'Cenário 3 (Painel)'}
for c in ('c1', 'c2', 'c3'):
    m = produtivas(os.path.join(AQUI, f'{c}-manual.blade.php'))
    l = produtivas(os.path.join(AQUI, f'{c}-livewind.blade.php'))
    tm += m; tl += l
    print(f"{rotulos[c]:<28}{m:>8}{l:>12}{(m-l)/m*100:>9.1f}%")
print('-' * 58)
print(f"{'Total / Média':<28}{tm:>8}{tl:>12}{(tm-tl)/tm*100:>9.1f}%")
