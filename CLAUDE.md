# CLAUDE.md — LiveWindUI

Contexto do projeto para o Claude Code. Leia este arquivo na primeira interação de cada sessão; ele descreve o que estamos construindo, como o código se organiza e quais convenções seguir.

---

## 1. Visão geral

**LiveWindUI** é uma biblioteca de componentes Blade reativos para a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire), distribuída como pacote Composer (`denisgusto/livewindui`). O objetivo é entregar componentes pré-configurados com integração nativa ao Livewire 3, estilização via Tailwind 4 puro (sem plugins), interatividade local via Alpine 3 e **zero JavaScript próprio adicional**.

**Proposta de valor (não negociar):**

1. Instalação via Composer + dois `@import` no `app.css` (Tailwind 4 é configurado por CSS, não por `tailwind.config.js`). Nada mais.
2. Reatividade Livewire pronta de fábrica (`wire:model`, `wire:click`, `wire:loading`, validação automática).
3. API Blade enxuta: o componente mais simples renderiza com **uma única tag**. Customização incremental via atributos.
4. Customização visual por classes Tailwind via `merge` de atributos. **Exceção (decidida em jun/2026, revisada para Tailwind 4):** theming e dark mode usam tokens semânticos via `@theme inline` + variáveis CSS — ver §10.
5. Composable: componentes complexos (DataTable) são compostos por componentes simples da própria biblioteca (Input, Select, Pagination).

Este projeto é o artefato de uma monografia de pós-graduação. **Decisões arquiteturais e de design da API ficam com o desenvolvedor humano** — você (Claude Code) executa codificação a partir de especificações já decididas. Não invente componentes não solicitados.

---

## 2. Stack e versões

| Camada | Tecnologia | Versão alvo |
|---|---|---|
| Linguagem | PHP | 8.1+ |
| Framework | Laravel | 10.x / 11.x / 12.x / 13.x |
| Reatividade | Livewire | 3.5+ / 4.3+ |
| JS client-side | Alpine.js | 3.x (carregado pelo Livewire) |
| CSS | Tailwind CSS | 4.x (sem DaisyUI, sem plugins; config via `@theme` no CSS) |
| Testes | Pest PHP | 2.x + `livewire/livewire` test helpers |
| Code style | Laravel Pint | versão atual (PSR-12) |

**Requisitos de compatibilidade:** Laravel 10+ simultaneamente. Não usar APIs exclusivas de uma versão sem fallback.

---

## 3. Estrutura de diretórios

```
livewindui/
├── composer.json                  # define vendor/name, autoload PSR-4, dependências, auto-discovery
├── README.md                      # documentação de uso
├── CLAUDE.md                      # este arquivo
├── docs/
│   ├── ROADMAP.md                 # marcos e iterações (espelha Quadro 5 do TCC)
│   └── sprints/
│       ├── SPRINT-01-base-fundamentals.md # iteração 1
│       ├── SPRINT-02-forms-feedback.md    # iteração 2
│       ├── SPRINT-03-data-navigation.md   # iteração 3
│       └── SPRINT-04-demo-tests-docs.md   # iteração 4
├── .claude/
│   └── skills/                    # skills repetitivas para o Claude Code
│       ├── create-component.md
│       ├── write-component-test.md
│       ├── add-demo-page.md
│       ├── handle-attribute-merge.md
│       └── setup-livewire-entangle.md
├── config/
│   └── livewindui.php              # prefixo, defaults globais
├── src/
│   ├── LiveWindUiServiceProvider.php
│   ├── Components/                # classes PHP de componentes complexos (Anonymous quando possível)
│   │   ├── Forms/
│   │   ├── Buttons/
│   │   ├── Feedback/
│   │   ├── Overlay/
│   │   ├── Data/
│   │   ├── Navigation/
│   │   └── Layout/
│   └── Support/                   # helpers internos (não componentes)
├── resources/
│   └── views/
│       └── components/            # templates Blade dos componentes
│           ├── button.blade.php
│           ├── input.blade.php
│           ├── ...
│           └── data-table.blade.php
├── tests/
│   ├── Pest.php
│   ├── TestCase.php               # estende Orchestra Testbench
│   ├── Unit/
│   └── Feature/
│       ├── ButtonTest.php
│       ├── InputTest.php
│       └── ...

```

**Regra:** se o componente é simples (sem lógica PHP), use **Blade anônimo** (apenas `.blade.php` em `resources/views/components/`). Se exige preparação de dados, defaults computados ou métodos de view, use componente com classe PHP em `src/Components/<Categoria>/<Nome>.php` estendendo `Illuminate\View\Component`.

---

## 4. Convenções de código

### 4.1 PHP

- **PSR-12** obrigatório. Rode `vendor/bin/pint` antes de qualquer commit.
- Namespaces: `LiveWindUi\` raiz para `src/`. Subnamespaces por categoria: `LiveWindUi\Components\Forms\Input`.
- Tipagem estrita: sempre `declare(strict_types=1);` no topo dos arquivos PHP.
- Properties e returns SEMPRE tipados.
- Use `readonly` em props de componentes quando possível.
- Não use facades dentro de componentes (use injeção via construtor). Exceção: `view()` helper em `render()`.

### 4.2 Blade

- Indentação de 4 espaços. Tags `@props` no topo de cada componente.
- Atributos de componentes em **kebab-case** no consumo (`max-width`, `wire-model`), camelCase nas props PHP.
- Sempre fazer **merge** das classes do consumidor com as classes do componente via `$attributes->class([...])->merge()`. Ver `.claude/skills/handle-attribute-merge.md`.
- Nunca usar `{{ $attributes }}` sem mergear classes — isso quebra customização.

### 4.3 Nomeação

- Prefixo de componentes: **`livewindui`** (configurável em `config/livewindui.php`). Consumo: `<x-livewindui::button />`.
- Arquivos Blade: kebab-case (`data-table.blade.php`, `icon-button.blade.php`).
- Classes PHP: PascalCase (`DataTable.php`).
- Variantes (props): valores em snake_case ou kebab-case consistentes; defina e mantenha. Sugestão: kebab-case (`variant="primary-outline"`).

### 4.4 CSS / Tailwind (Tailwind 4)

- **Um único CSS de tema** (`resources/css/livewindui.css`) que define os tokens via `@theme inline` + variáveis CSS (claro/escuro). Fora dele, nenhum outro `.css` próprio. Ver §10.
- **Sem `tailwind.preset.js`.** Tailwind 4 é configurado por CSS (`@theme`), não por arquivo JS. Não recriar preset.
- Não usar `@apply` em arquivos CSS da biblioteca.
- **API semântica de cor (regra central):** nos templates use **sempre** os tokens semânticos — nunca cores literais do Tailwind (`bg-indigo-600`, `bg-gray-100`) para superfícies/estado:
  - Marca/destaque: `bg-accent`, `text-accent-foreground`, `hover:bg-accent-content`.
  - Estado: `danger`, `success`, `warning` (cada um com par `-foreground`).
  - Neutros: `surface`/`surface-foreground` (fundo+texto base), `muted`/`muted-foreground` (superfície/ texto secundário), `border`.
- **Não use classes `dark:` em componentes.** Os tokens neutros já trocam sob `.dark` (o `@theme inline` resolve isso). Escrever `dark:` é sinal de cor hardcoded — corrija para o token.
- Opacidade nos tokens é válida e idiomática no v4 (`hover:bg-muted/80`, `bg-danger/90`) — resolvida via `color-mix`.
- **Não** depender de plugins (DaisyUI, forms, typography).

### 4.5 JavaScript

- **Zero JS próprio** da biblioteca. Toda interatividade local é via Alpine.js (`x-data`, `x-show`, `x-transition`, etc.) escrita inline nos templates Blade.
- Para sincronização com Livewire em overlays (Modal, Dropdown), use `@entangle()` — ver skill `setup-livewire-entangle.md`.

---

## 5. Padrões de componente

### 5.1 Estrutura padrão de um componente Blade

```blade
@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'loading' => null, // string: nome do método/property Livewire para wire:loading
])

@php
    // Variantes 100% semânticas, sobre os tokens da §10. NÃO use cores literais
    // do Tailwind (bg-indigo-600) nem classes `dark:` — os tokens já trocam sob `.dark`.
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-md transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2';
    $variantClasses = match($variant) {
        'primary' => 'bg-accent text-accent-foreground hover:bg-accent-content focus-visible:outline-accent',
        'secondary' => 'bg-muted text-surface-foreground hover:bg-muted/80 focus-visible:outline-muted-foreground',
        'danger' => 'bg-danger text-danger-foreground hover:bg-danger/90 focus-visible:outline-danger',
        'outline' => 'border border-border bg-surface text-surface-foreground hover:bg-muted',
        'ghost' => 'text-surface-foreground hover:bg-muted',
        default => 'bg-accent text-accent-foreground hover:bg-accent-content',
    };
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
        default => 'px-4 py-2 text-sm',
    };
@endphp

<button {{ $attributes->class([$baseClasses, $variantClasses, $sizeClasses])->merge(['type' => 'button']) }}>
    @if($loading)
        <span wire:loading wire:target="{{ $loading }}" class="mr-2">
            <x-livewindui::spinner size="sm" />
        </span>
        <span wire:loading.remove wire:target="{{ $loading }}" class="contents">
            {{ $slot }}
        </span>
    @else
        {{ $slot }}
    @endif
</button>
```

**Pontos não negociáveis:**
- `@props([...])` no topo, com defaults sensatos.
- Classes Tailwind compostas via `match()` (legível, sem strings gigantes).
- `$attributes->class([...])->merge([...])` para que o consumidor possa sobrescrever.
- `wire:loading` automático quando a prop `loading` é fornecida.
- Sem JS inline próprio. Interatividade via Alpine só quando necessário.

### 5.2 Componente com classe PHP

Use classe PHP **só** quando há lógica que não cabe em `@php` no topo do Blade. Critério: se for mais de 15 linhas de lógica ou se requer injeção de dependência, vai para classe.

```php
<?php
declare(strict_types=1);

namespace LiveWindUi\Components\Data;

use Illuminate\View\Component;
use Illuminate\View\View;

final class DataTable extends Component
{
    public function __construct(
        public readonly array $columns = [],
        public readonly string $sortBy = '',
        public readonly string $sortDirection = 'asc',
        public readonly bool $searchable = true,
        public readonly bool $paginated = true,
    ) {}

    public function render(): View
    {
        return view('livewindui::components.data-table');
    }
}
```

### 5.3 Service Provider — registro de componentes

Componentes são registrados no `boot()` do `LiveWindUiServiceProvider`. Use `Blade::componentNamespace()` para registrar tudo de uma vez com namespace `livewindui`:

```php
Blade::componentNamespace('LiveWindUi\\Components', 'livewindui');
```

Componentes Blade anônimos em `resources/views/components/` são registrados via `Blade::anonymousComponentPath()` usando `config('livewindui.prefix', 'livewindui')`.

---

## 6. Comandos comuns

```bash
# Setup inicial (executar uma vez após clonar)
composer install

# Code style
vendor/bin/pint              # corrige
vendor/bin/pint --test       # apenas verifica

# Testes
vendor/bin/pest              # tudo
vendor/bin/pest --filter=ButtonTest

# App demo
cd demo && php artisan serve
cd demo && npm run dev       # vite watch

# Inspecionar bundle JS final da demo (verificar RNF01)
cd demo && npm run build && du -h public/build/assets/*.js
```

---

## 7. Como o Claude Code deve trabalhar aqui

1. **Antes de implementar**, leia o sprint relevante (`docs/sprints/SPRINT-XX-*.md`) e o `docs/ROADMAP.md`.
2. Para tarefas repetitivas, **leia a skill correspondente em `.claude/skills/`** antes de codar. Não invente padrões — siga os existentes.
3. Implemente um componente de cada vez. Para cada componente:
   - Crie o template Blade (e a classe PHP, se necessário).
   - Escreva o teste Pest (ver `.claude/skills/write-component-test.md`).
   - Rode `vendor/bin/pint` e `vendor/bin/pest --filter=<NomeDoComponenteTest>`.
   - Adicione uma página de demo (ver `.claude/skills/add-demo-page.md`).
4. **Não altere** decisões arquiteturais documentadas neste CLAUDE.md sem confirmação do desenvolvedor humano. Se você acha que algo aqui está errado, **pergunte primeiro**.
5. **Não crie componentes fora do escopo do sprint atual**, mesmo se parecerem úteis. Anote no `docs/ROADMAP.md` como sugestão para iteração futura.
6. Se um requisito do sprint estiver ambíguo, marque com `// TODO(@dev):` no código e siga em frente; pergunte ao final.

---

## 8. Anti-padrões a evitar

- ❌ Criar arquivos CSS (`.css`, `.scss`) próprios da biblioteca — **exceto** o único CSS de tema (`resources/css/livewindui.css`), ver §10.
- ❌ Adicionar dependências npm (a biblioteca tem zero `package.json`).
- ❌ Usar `setTimeout` ou JS imperativo solto em templates. Use Alpine.
- ❌ Hardcode de cores fora do sistema Tailwind (sem `#ff5733` direto, use `bg-red-500` ou `bg-[#ff5733]` se realmente necessário).
- ❌ Esquecer `$attributes->class([...])->merge()` — quebra customização do consumidor.
- ❌ Componentes que carregam dados sozinhos via Eloquent. O consumidor sempre fornece os dados via props.
- ❌ Eventos Livewire com nomes genéricos. Use prefixo: `livewindui:toast.show`, `livewindui:modal.close`.
- ❌ Lógica de negócio dentro de componentes. Componentes são puramente apresentacionais.

---

## 9. Onde encontrar mais contexto

- Especificação completa do projeto: ver TCC (`TCC_LiveWindUI_Unificado.docx` — Capítulos 1 a 4).
- Requisitos funcionais: Quadro 6 do TCC (RF01–RF10).
- Requisitos não funcionais: Quadro 7 do TCC (RNF01–RNF07).
- Escopo de componentes por iteração: Quadro 5 do TCC e arquivos `SPRINT-*.md`.

---

## 10. Theming e Dark Mode (Tailwind 4 — sistema semântico de tokens)

Decisão do desenvolvedor humano (jun/2026, revisada para Tailwind 4): theming + dark mode
por **tokens semânticos**, estilo shadcn/ui. Sai o preset JS, entra `@theme` no CSS. A
API de cor dos componentes é **só semântica** — `variant="primary|danger|..."` resolvendo
para tokens; **não há prop de cor literal** (`color="indigo"` foi removida).

### Arquitetura

- **`resources/css/livewindui.css`** — único CSS da lib. Três blocos:
  1. `@custom-variant dark (&:where(.dark, .dark *));` → dark mode por classe `.dark`
     (o default do v4 é `prefers-color-scheme`).
  2. Valores crus dos *roles* como variáveis (canais RGB) em `:root` e `.dark`. **É só
     isto que o consumidor sobrescreve para re-tematizar tudo.**
  3. `@theme inline { --color-accent: rgb(var(--lw-accent)); ... }` → expõe cada role
     como token Tailwind. O `inline` é o que faz `bg-accent`, `border-border`, etc.
     **seguirem o `.dark` automaticamente**.
  - Publicável via `vendor:publish --tag=livewindui-theme`.
- **Sem `tailwind.preset.js`.** Removido — não recriar.

### Conjunto de tokens (todos tematizáveis, todos com par claro/escuro)

| Categoria | Tokens |
|---|---|
| Marca/destaque | `accent`, `accent-content` (hover), `accent-foreground` |
| Estado | `danger` + `danger-foreground`, `success` + `success-foreground`, `warning` + `warning-foreground` |
| Neutros | `surface` + `surface-foreground`, `muted` + `muted-foreground`, `border` |

### Regras ao editar/criar componentes

- **Cor sempre via token semântico.** Fundo base → `bg-surface text-surface-foreground`;
  superfície/hover neutro → `bg-muted` / `hover:bg-muted`; texto secundário →
  `text-muted-foreground`; borda → `border-border`; destaque → `bg-accent ...`.
- **Nunca** cores literais do Tailwind (`bg-gray-100`, `bg-indigo-600`) para superfícies/estado.
- **Nunca** classes `dark:` — os tokens neutros já trocam sob `.dark`. Se você sentiu
  necessidade de um `dark:`, é porque hardcodou uma cor; troque pelo token.
- Hover/estados sutis usam opacidade no token (`hover:bg-muted/80`, `bg-danger/90`).
- Componente de referência canônico: `resources/views/components/button.blade.php`.

### Re-tematizar (consumidor)

Basta sobrescrever os valores crus no próprio `app.css`, depois do `@import` da lib:

```css
:root { --lw-accent: 34 197 94; --lw-accent-content: 22 163 74; } /* sistema todo verde */
.dark { --lw-accent: 74 222 128; --lw-accent-content: 134 239 172; }
```

- **`config('livewindui.theme.accent')`** — nome da cor padrão (informativo; a cor real
  vem das variáveis CSS).
- **Dark mode** = classe `.dark` no `<html>`, aplicada pelo consumidor (a demo usa um
  script anti-flash + `window.LiveWindUIAppearance`).

> ⚠️ **Migração em andamento:** o Button já está 100% no sistema semântico. Os demais
> componentes ainda contêm cores neutras literais + `dark:` (continuam funcionando no v4);
> ao tocar em qualquer um deles, **migre-o para os tokens** seguindo o Button.

Última atualização deste CLAUDE.md: jun/2026 — migração para Tailwind 4 + tokens semânticos (§10).
