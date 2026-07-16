# Plano de migração — Opção B (bundle JS servido) + Colocation

Status: **proposta, aguardando aprovação**. Não iniciar nenhuma fase antes do OK.

## Objetivo

Passar a LiveWindUI de "zero bundle" para uma arquitetura com **toolchain JS de
primeira classe** (Vite + eslint + vitest) e **`dist/` servido**, mantendo:

- a DX de instalação (consumidor **não** roda npm);
- os **149 testes** verdes a cada fase;
- Alpine trivial (`x-show`, `x-data="{ open:false }"`) **inline** no Blade;
- lógica com estado/algoritmo (toast, calendar, signature) em `Alpine.data()` no bundle.

E adotar **colocation** (classe + view do componente na mesma pasta, estilo TallStackUI).

## Princípios (não negociar durante a migração)

1. **Verde sempre.** Cada fase termina com `pest` + `pint --test` verdes. Fases que
   mexem em JS também terminam com `npm run test` (vitest) e `npm run lint` verdes.
2. **Uma fase = um PR mental.** Sem misturar colocation com migração de JS.
3. **Sem regressão de HTML.** Testes de componente assertam saída renderizada; ela não
   muda (exceto onde um teste asserta JS inline que virou bundle — ver Fase 4).

## Decisões a confirmar (bloqueiam o início)

| # | Decisão | Recomendação |
|---|---|---|
| D1 | **Colocation ⇒ class-per-component?** Anonymous components esperam views num path flat; colocar view por pasta empurra para **toda componente ter classe PHP** (estilo TSUI). Isso muda a regra do CLAUDE.md §3 ("simples → blade anônimo"). | **Sim, class-per-component.** Uniforme, casa com colocation e com o TSUI. |
| D2 | **Entrega do `dist/`**: publicado (`vendor:publish`) vs **servido por rota** (estilo Livewire/TSUI) vs import via npm | **Servido por rota** (`/livewind/livewind.js`) + opção de publish. Zero passo extra pro consumidor. |
| D3 | **Commitar `dist/` no repo?** (composer install não roda npm) | **Sim**, com CI que rebuilda e verifica que `dist/` está atualizado. |
| D4 | **Nível inicial do phpstan** | Level **5**, subindo depois. |
| D5 | **Publicar pacote npm** (para quem gerencia o próprio Alpine)? | **Não agora** — só asset servido. Reavaliar depois. |

## Fases

### Fase 0 — Baseline & guardrails
- Confirmar `pest` (149) + `pint --test` verdes.
- Adicionar `phpstan.neon` num nível que **já passa** (baseline), sem refatorar nada.
- Adicionar `testbench.yaml` (mover config do `TestCase` para lá, opcional).
- **DoD:** CI roda pest + pint + phpstan; tudo verde. Nenhuma mudança de comportamento.

### Fase 1 — Colocation (mecânico, sem JS)
- Se D1 = sim: dar classe a todo componente que ainda é anônimo (alert, card, divider,
  etc.), `render()` apontando para a view colocada.
- Mover para `src/Components/<Nome>/<Nome>.php` + `src/Components/<Nome>/<nome>.blade.php`.
- ServiceProvider: registrar o novo path de views (namespace `livewind::` apontando para
  `src/Components`, resolução por convenção).
- `resources/views/components/` é esvaziado; `resources/css/` e `resources/views/runtime/`
  permanecem.
- **DoD:** todos os componentes colocados; saída idêntica; 149 verdes. Atualizar CLAUDE.md §3.
- **Risco:** registro de view/prefixo. **Mitigação:** migrar 2–3 componentes primeiro,
  validar resolução `<x-livewind::...>`, depois o resto em lote.

### Fase 2 — Toolchain JS (bundle vazio, sem migrar lógica)
- `package.json` (devDeps: vite, eslint, prettier, vitest), `vite.config.js`,
  `eslint.config.mjs`, `.prettierrc`, `.gitignore` (node_modules).
- `js/index.js` — entry que exporta `Livewind()` (registra Alpine.data — vazio por ora).
- `npm run build` → `dist/livewind.js` (commitado).
- Rota servindo o `dist/` (D2) + `@livewindScripts` passa a injetar
  `<script src="/livewind/livewind.js" ...>` **além** do container de toast.
- **DoD:** `npm run build/lint/test` ok; `@livewindScripts` injeta o script + toast;
  149 verdes (nada de comportamento mudou ainda).

### Fase 3 — Migrar o **toast** para `Alpine.data` (prova de conceito)
- Mover as ~80 linhas de Alpine inline do `toast.blade.php` para
  `js/components/toast.js` como `Alpine.data('lwToast', () => ({...}))`.
- `toast.blade.php` vira fino: `x-data="lwToast(@js($config))"`.
- **Testes que mudam** (esperado): `ToastTest` e `RuntimeDirectivesTest` assertavam JS
  inline (`addEventListener('livewind:toast'`, etc.). Passam a assertar `x-data="lwToast"`
  + presença do `<script>` do bundle. **Adicionar vitest** cobrindo a lógica (timer,
  dedupe, pause/resume, max).
- **DoD:** toast funciona via bundle; PHP tests atualizados; vitest verde; smell resolvido.

### Fase 4 — Higiene: lang + install + phpstan tightening
- `lang/en/livewind.php` + `lang/pt_BR/livewind.php`; trocar strings PT hardcoded
  (`data-table`, `pagination`, aria-labels) por `__('livewind::...')`.
- `Console\InstallCommand` (`php artisan livewind:install`): injeta `@import`/`@source`
  no `app.css`, publica config/views, imprime as diretivas de layout.
- Subir o nível do phpstan e corrigir o que aparecer.
- **DoD:** strings traduzíveis; `livewind:install` funciona; phpstan no nível-alvo verde.

### Fase 5 — Componentes novos: **Calendar** e **Signature**
- Já nascem no modelo B: classe PHP (colocada) + blade + `js/components/{calendar,
  signature}.js` como `Alpine.data`, + teste PHP (feature) + vitest (unit).
- Signature provavelmente encapsula `signature_pad` (dep npm da lib, entra no bundle).
- **DoD:** componentes + testes PHP e JS verdes; documentados no README.

## Mecânica de distribuição (D2/D3)

- `dist/livewind.js` **commitado**; servido por uma rota do pacote com `Content-Type`
  e cache. `@livewindScripts` referencia essa rota (com versão/hash para cache-bust).
- Livewire + `wire:navigate`: o `<script>` do bundle precisa carregar uma vez; usar o
  padrão de asset persistente (à la Livewire) para não re-executar em cada navegação.
- CI: `npm ci && npm run build && git diff --exit-code dist/` garante que o `dist/`
  commitado bate com a fonte.

## Rollback

Cada fase é isolada e revertível por git. As Fases 0–1 (baseline + colocation) não
introduzem JS; se a decisão de B for revista, o toolchain (Fases 2+) pode ser removido
sem desfazer a colocation.

## Impacto em docs (ao final)

- CLAUDE.md: §1 (proposta de valor — de "zero bundle" para "bundle mínimo servido"),
  §3 (colocation + class-per-component), §4.5 (política de JS), §10 (inalterado).
- README: seção de instalação (asset servido automático), Layout (já pronta).
- ROADMAP: marcar calendar/signature.
