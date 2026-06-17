# SPRINT-01 — Estrutura base e componentes fundamentais

**Duração estimada:** 1-2 semanas
**Pré-requisito:** Nenhum. Este é o primeiro sprint.
**Iteração do Quadro 5 do TCC:** 1

---

## Objetivo

Estabelecer a infraestrutura do pacote (Service Provider, registro de componentes, prefixo configurável, integração Tailwind) e implementar os três componentes essenciais que validam a arquitetura: **Button**, **Input**, **Alert**.

Ao final do sprint, deve ser possível:
1. Instalar o pacote em um Laravel 11 fresh via Composer (path repository local).
2. Adicionar uma linha no `tailwind.config.js`.
3. Usar `<x-livewindui::button>`, `<x-livewindui::input>` e `<x-livewindui::alert>` em qualquer Blade da aplicação consumidora — funcionando reativamente.

---

## Entregáveis

### 1. Infraestrutura do pacote

- [x] `composer.json` com nome `denisgusto/livewindui`, PSR-4 autoload, dependências (`php >= 8.1`, `illuminate/* >= 10`, `livewire/livewire >= 3.5`), e auto-discovery do Service Provider.
- [x] `src/LiveWindUiServiceProvider.php`:
  - `register()`: merge do config (`livewindui.php`).
  - `boot()`: `loadViewsFrom`, `Blade::componentNamespace`, `publishes` para config e views.
- [x] `config/livewindui.php` com chaves: `prefix` (default `livewindui`) e defaults de componentes por categoria.
- [x] `tests/TestCase.php` estendendo `Orchestra\Testbench\TestCase` registrando Livewire + LiveWindUi providers.
- [x] `tests/Pest.php` apontando para `TestCase`.
- [x] README.md inicial com seções: Instalação, Configuração Tailwind, Uso básico (placeholder).

### 2. Componente Button

Localização: `resources/views/components/button.blade.php` (anônimo).

Props (todas com default):
- `variant`: `primary` | `secondary` | `danger` | `outline` | `ghost` — default `primary`.
- `size`: `sm` | `md` | `lg` — default `md`.
- `icon`: string opcional (nome de ícone Heroicons ou null) — placeholder; renderização do ícone fica para iteração 4 se preciso.
- `loading`: string com nome do método/property para `wire:loading wire:target` — null por padrão.
- `confirm`: string opcional para `wire:confirm` — null por padrão.

Comportamento:
- Renderiza `<button>` com classes Tailwind compostas via `match()`.
- Quando `loading` é passado, exibe spinner durante `wire:loading wire:target=<loading>` e oculta o slot principal.
- Quando `confirm` é passado, adiciona `wire:confirm="<texto>"`.
- `$attributes->class([...])->merge(['type' => 'button'])` — sempre.
- ARIA: `aria-busy="true"` quando em loading state (via Alpine ou atributo Blade condicional).

### 3. Componente Input

Localização: `resources/views/components/input.blade.php` (anônimo).

Props:
- `model`: string com nome da prop Livewire (atalho para wire:model) — null.
- `modelLive`: bool — usar `wire:model.live` ao invés de `wire:model` — false.
- `label`: string opcional.
- `hint`: string opcional (texto auxiliar abaixo).
- `type`: default `text` (sobrescrevível via attribute merge).
- `prefix`, `suffix`: slots nomeados opcionais para ícone/texto à esquerda/direita.

Comportamento:
- Wrapper `<div>` com label + input + hint/error.
- Detecta erro com `$errors->has($model)` e aplica classes Tailwind de erro (`border-red-500`, `text-red-600`).
- Quando há erro, exibe `$errors->first($model)` no lugar do hint.
- Atributos arbitrários (`placeholder`, `maxlength`, `pattern`, etc) propagam via `$attributes->merge([...])` no `<input>`.
- ARIA: `aria-invalid="true"` quando há erro, `aria-describedby` apontando para o id do hint/error.

### 4. Componente Alert

Localização: `resources/views/components/alert.blade.php` (anônimo).

Props:
- `variant`: `success` | `info` | `warning` | `danger` — default `info`.
- `title`: string opcional.
- `dismissible`: bool — adiciona botão de fechar — default false.
- `autoDismiss`: int (milissegundos) — fecha automaticamente após X ms — null por padrão.

Comportamento:
- Renderiza `<div role="alert">` com classes por variant.
- Quando `dismissible` ou `autoDismiss`, envolve em `x-data="{ show: true }"` e botão `x-on:click="show = false"`.
- Quando `autoDismiss`, adiciona `x-init="setTimeout(() => show = false, {{ $autoDismiss }})"`.
- `x-show="show"` + `x-transition`.
- Slot principal é o corpo da mensagem.
- ARIA: `role="alert"` + `aria-live="polite"` (ou `assertive` para `danger`).

### 5. Componente Spinner (auxiliar, usado pelo Button)

Localização: `resources/views/components/spinner.blade.php` (anônimo).

Props:
- `size`: `sm` | `md` | `lg` — default `md`.

Renderiza um SVG spinner Tailwind animado. `aria-hidden="true"` (decorativo, não anuncia).

### 6. Testes Pest

- [x] `tests/Feature/Buttons/ButtonTest.php` — 4 testes obrigatórios (renderização, variantes, tamanhos, merge, wire:click + loading).
- [x] `tests/Feature/Forms/InputTest.php` — renderização, label, hint, exibição de erro via componente Livewire de teste, wire:model funcional.
- [x] `tests/Feature/Feedback/AlertTest.php` — variantes, dismissible, autoDismiss (snapshot HTML com x-init), role correto.
- [x] Todos passam: `vendor/bin/pest`.

### 7. Páginas de demo (na app `demo/`)

- [x] `/components/button` — showcase de variantes/tamanhos/loading.
- [x] `/components/input` — showcase com formulário Livewire mostrando wire:model e validação.
- [x] `/components/alert` — showcase de variantes, dismissible e autoDismiss.
- [x] Atualizar home `/` para listar essas 3 demos.

---

## Critérios de aceitação

1. `composer require denisgusto/livewindui` (via path local) **funciona sem erro** num Laravel 11 fresh.
2. Após adicionar `./vendor/denisgusto/livewindui/resources/views/**/*.blade.php` ao `content` do `tailwind.config.js`, **todas as classes** dos componentes são geradas corretamente no CSS final.
3. `<x-livewindui::button>Salvar</x-livewindui::button>` renderiza um botão **funcional e estilizado** num Blade de teste — sem ter que escrever uma linha de CSS ou JS na aplicação.
4. `<x-livewindui::input model="email" label="E-mail" />` num componente Livewire vincula corretamente a `$email`, exibe erros do `$rules`, e o erro some quando o input é corrigido.
5. `<x-livewindui::alert variant="success" :autoDismiss="3000">Salvo!</x-livewindui::alert>` aparece e some sozinho após 3 segundos.
6. `vendor/bin/pest` passa 100%.
7. `vendor/bin/pint --test` passa sem warnings.
8. Bundle JS final da app demo, depois de `npm run build`, **não contém JavaScript próprio da LiveWindUI** (apenas Livewire + Alpine).

---

## Sequência sugerida de execução para o Claude Code

1. Ler `CLAUDE.md` e `.claude/skills/create-component.md`.
2. Criar `composer.json`, `src/LiveWindUiServiceProvider.php`, `config/livewindui.php`.
3. Criar `tests/TestCase.php`, `tests/Pest.php`. Rodar `vendor/bin/pest` (deve passar 0 testes sem erro).
4. Criar Spinner (`resources/views/components/spinner.blade.php`). Teste opcional.
5. Criar Button (template + teste). Rodar `vendor/bin/pest --filter=ButtonTest`. Verde antes de seguir.
6. Criar Input (template + teste). Rodar filtro. Verde antes de seguir.
7. Criar Alert (template + teste). Rodar filtro. Verde antes de seguir.
8. Criar app demo (`demo/`) com `composer create-project laravel/laravel demo`, configurar repository path para apontar pra `../`.
9. Adicionar páginas de demo seguindo `.claude/skills/add-demo-page.md`.
---

## Notas e armadilhas

- O **prefixo** dos componentes vem de `config('livewindui.prefix', 'livewindui')` e já deve ser respeitado no Service Provider.
- **Não use `@apply`** em nenhum arquivo CSS. Não crie arquivos CSS.
- O `auto-discovery` do Service Provider depende da seção `extra.laravel.providers` no `composer.json`. Confirme antes de testar.
- A app demo é um **projeto separado** do pacote. Não confunda os `composer.json`. A demo aponta para o pacote via `repositories` do tipo `path`.

---

## Saída de revisão

- [x] Quantos componentes implementados: 4 (Button, Input, Alert, Spinner).
- [x] Quantos testes Pest: suíte completa com 60 testes / 171 assertions (`vendor/bin/pest --compact`).
- [x] Bundle JS da demo: 45,65 KB bruto / 17,72 KB gzip (`npm run build`), sem JavaScript próprio da LiveWindUI.
- [x] Páginas demo validadas por HTTP 200: `/`, `/components/button`, `/components/input`, `/components/alert`.
- [x] `npm audit --audit-level=critical` da demo: zero vulnerabilidades após remover `concurrently`.
- [ ] Bugs/limitações conhecidas levadas para Sprint 2:
  - Demo Laravel 11 apresenta advisories de segurança no `laravel/framework` em `composer audit`; o pacote LiveWindUI não é o pacote afetado.

**Próximo sprint:** `SPRINT-02-forms-feedback.md`.
