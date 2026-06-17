# SPRINT-02 — Formulários e feedback

**Duração estimada:** 2 semanas
**Pré-requisito:** SPRINT-01 concluído (Button, Input, Alert funcionando + infra do pacote).
**Iteração do Quadro 5 do TCC:** 2

---

## Objetivo

Expandir o conjunto de formulários (Select, Textarea, Checkbox, Radio, Toggle) e implementar componentes de feedback visual mais sofisticados (Toast com eventos globais, Modal com sincronização Alpine↔Livewire).

Ao final do sprint, deve ser possível construir um **formulário de cadastro completo** usando apenas componentes da biblioteca, com validação reativa, modal de confirmação e toast de sucesso.

---

## Entregáveis

### 1. Componente Select

`resources/views/components/select.blade.php` (anônimo).

Props:
- `model`, `modelLive`, `label`, `hint`, `placeholder` (default `'Selecione...'`).
- `options`: array associativo `[valor => rótulo]` OU array de objetos (`['value' => ..., 'label' => ...]`).

Comportamento:
- Renderiza `<select>` nativo HTML estilizado via Tailwind.
- Aceita slot principal para options customizadas se `options` não for fornecido.
- Erro/wire:model/ARIA seguindo padrão do Input.

### 2. Componente Textarea

`resources/views/components/textarea.blade.php` (anônimo).

Props:
- `model`, `modelLive`, `label`, `hint`, `rows` (default `4`).
- `maxLength`: int opcional — quando presente, ativa contador reativo via Alpine.
- `autoResize`: bool — quando true, Alpine ajusta altura conforme conteúdo.

Comportamento:
- Renderiza `<textarea>` com Tailwind + erro states.
- Contador: `<p class="text-xs text-gray-500"><span x-text="value.length"></span>/{{ $maxLength }}</p>` com `x-data="{ value: $wire.{{ $model }} }"`.
- Auto-resize: `x-on:input` ajusta `style.height`.

### 3. Componentes Checkbox e Radio

`resources/views/components/checkbox.blade.php` e `radio.blade.php` (anônimos).

Props comuns:
- `model`, `label`, `description` (texto auxiliar abaixo do label).
- `value` (para Radio e Checkbox como parte de grupo).

Comportamento:
- Wrapper `<label>` clicável envolvendo input + texto.
- Estado checked aplica estilos visuais via `peer:checked:*` do Tailwind.
- ARIA correto (input nativo já tem semântica; só garantir label associado).

### 4. Componente Toggle

`resources/views/components/toggle.blade.php` (anônimo).

Props:
- `model`, `label`, `description`.
- `size`: `sm` | `md` | `lg`.

Comportamento:
- Switch visual estilizado (não checkbox nativo aparente).
- `<button role="switch" aria-checked="..." x-data="{ checked: @entangle($model) }">` — só usar @entangle se `model` for fornecido.
- `aria-checked` sincronizado.
- Clique alterna o estado; ENTER e SPACE também (foco no botão).

### 5. Componente Toast e Container

`resources/views/components/toast.blade.php` — container global a ser colocado no layout principal da app demo (uma vez).
`resources/views/components/toast-item.blade.php` — item individual (usado internamente).

Props do container:
- `position`: `top-right` | `top-left` | `bottom-right` | `bottom-left` | `top-center` — default `top-right`.
- `duration`: int (ms) default — quanto cada toast fica visível — default `4000`.

Comportamento:
- Container escuta eventos globais Livewire `livewindui:toast.show` via `x-on:livewire:init.window` ou `Livewire.on(...)` dentro de Alpine.
- Cada toast tem `variant` (`success` | `info` | `warning` | `danger`), `message` (obrigatório), `title` (opcional).
- Animações de entrada e saída via `x-transition`.
- Auto-dismiss após `duration` ms.
- Botão de fechar opcional.
- ARIA: `role="status"` para info/success, `role="alert"` para warning/danger.
- Stack: novos toasts aparecem empilhados.

API de uso pelo desenvolvedor:

```php
// no componente Livewire
$this->dispatch('livewindui:toast.show',
    variant: 'success',
    message: 'Contato salvo com sucesso!',
    title: 'Sucesso'
);
```

### 6. Componente Modal

Ver `.claude/skills/setup-livewire-entangle.md` — implementa o padrão descrito lá.

`resources/views/components/modal.blade.php` (anônimo).

Props:
- `name` (obrigatório): identificador para eventos globais.
- `maxWidth`: `sm` | `md` | `lg` | `xl` | `2xl` — default `md`.
- `closeable`: bool — default `true` (mostra X e fecha com ESC/click-outside).
- `show`: bool — estado inicial (para integração com @entangle externo).

Comportamento (resumo — ver skill para detalhes):
- `x-data` com estado `show` + métodos `open()` / `close()`.
- Escuta `livewindui-modal-open` / `livewindui-modal-close` com discriminação por `name`.
- ESC fecha (se closeable).
- Click no backdrop fecha (se closeable).
- `x-trap.noscroll` para trap focus.
- ARIA: `role="dialog"`, `aria-modal="true"`, `aria-labelledby="modal-title-{name}"`.
- Body com `overflow-hidden` enquanto aberto.

API de uso pelo desenvolvedor:

```blade
<x-livewindui::modal name="confirm-delete" max-width="sm">
    <div class="p-6">
        <h2 id="modal-title-confirm-delete" class="text-lg font-semibold">Confirmar exclusão?</h2>
        <p class="mt-2 text-gray-600">Essa ação não pode ser desfeita.</p>
        <div class="mt-4 flex gap-2 justify-end">
            <x-livewindui::button variant="ghost" x-on:click="close()">Cancelar</x-livewindui::button>
            <x-livewindui::button variant="danger" wire:click="delete">Excluir</x-livewindui::button>
        </div>
    </div>
</x-livewindui::modal>
```

```php
// no Livewire
public function askDelete(): void
{
    $this->dispatch('livewindui-modal-open', name: 'confirm-delete');
}
```

### 7. Testes Pest

- [x] `SelectTest.php` — opções como array assoc, como array de objetos, wire:model, erro.
- [x] `TextareaTest.php` — contador (snapshot HTML com x-text), auto-resize (snapshot x-on:input), maxLength.
- [x] `CheckboxTest.php` e `RadioTest.php` — renderização, label associado, wire:model.
- [x] `ToggleTest.php` — role="switch", aria-checked, alternância.
- [x] `ToastTest.php` — disparar evento Livewire e assertar que container renderiza o item; auto-dismiss via x-init (snapshot).
- [x] `ModalTest.php` — open via evento, close via ESC (snapshot keydown.escape), aria-modal, trap focus presente.

### 8. Páginas de demo

- [ ] `/components/select`, `/components/textarea`, `/components/checkbox`, `/components/radio`, `/components/toggle`.
- [ ] `/components/toast` — botões que disparam toasts de cada variant.
- [ ] `/components/modal` — botões que abrem modais com diferentes maxWidth.
- [ ] **Atualizar `/contatos`** para versão funcional v1:
  - DataTable virá no Sprint 3, mas já estruturar tabela com lista estática.
  - Formulário de novo contato (Input + Select para categoria + Toggle ativo + Textarea observações) em Modal.
  - Submit do formulário dispara Toast de sucesso.
  - Botão de excluir dispara Modal de confirmação → ação → Toast.

---

## Critérios de aceitação

1. Os 5 componentes de formulário (Select, Textarea, Checkbox, Radio, Toggle) funcionam com `wire:model` automaticamente.
2. Erros de validação aparecem em todos eles sem código extra do consumidor.
3. Toast: chamar `$this->dispatch('livewindui:toast.show', variant: 'success', message: 'X')` em qualquer Livewire da app demo **exibe o toast com a variante correta, auto-dismiss em 4s, animação suave**.
4. Modal: `$this->dispatch('livewindui-modal-open', name: 'X')` abre o modal `name="X"`. ESC fecha. Click fora fecha. Foco fica preso dentro enquanto aberto.
5. Página `/contatos` permite criar um contato com Modal + Form completo, validar, submeter, ver Toast de sucesso, ver na lista, excluir com confirmação.
6. `vendor/bin/pest` passa 100%.
7. Cobertura ≥ 70% nos componentes essenciais.
8. axe-core na `/contatos` não retorna violações críticas.
9. Bundle JS da demo continua **sem JavaScript próprio** da LiveWindUI.

---

## Sequência sugerida

1. Ler `.claude/skills/setup-livewire-entangle.md` antes de iniciar.
2. Implementar Select, Textarea, Checkbox, Radio, Toggle em sequência. Para cada: template → teste → demo.
3. Implementar Toast (container + item + integração no layout da demo).
4. Implementar Modal seguindo a skill linha por linha.
5. Refatorar `/contatos` para usar todos os componentes acima.
6. Rodar suite completa, axe-core, build da demo.

---

## Notas e armadilhas

- Cuidado com **loops de @entangle**: se o componente pai já tem `@entangle('showModal').live`, o Modal interno não deve duplicar a mesma entangle.
- Toast usa **eventos globais** Livewire — o container precisa estar no layout principal da demo, não em cada página.
- Toggle precisa de `role="switch"` para acessibilidade — não use checkbox escondido com label estilizado se o resultado semântico for confuso.
- O Modal usa `x-trap` que requer o plugin `@alpinejs/focus`. Confirme que está disponível (vem com Livewire 3 por default).

---

## Saída de revisão

- [x] Componentes implementados: 7/7 (Select, Textarea, Checkbox, Radio, Toggle, Toast, Modal).
- [x] Testes Pest: 60 testes / 171 assertions no total da suíte atual. Cobertura: ___% .
- [ ] axe-core /contatos: ___ críticas, ___ sérias.
- [ ] Bundle JS: ___ KB (confirmar zero próprio).
- [ ] Páginas demo: 7 individuais + /contatos v1.

**Próximo sprint:** `SPRINT-03-data-navigation.md`.
