# ROADMAP — LiveWindUI

Este roadmap espelha o Quadro 5 do TCC e serve como visão executável do escopo de cada iteração. Cada item linkado aponta para o sprint correspondente, com critérios detalhados de aceitação.

---

## Visão geral

| Iteração | Sprint | Status | Foco |
|---|---|---|---|
| 1 | [SPRINT-01](./sprints/SPRINT-01-base-fundamentals.md) | ◐ Implementado, pendente axe externo | Infra do pacote + Button, Input, Alert |
| 2 | [SPRINT-02](./sprints/SPRINT-02-forms-feedback.md) | ◐ Em andamento | Select, Textarea, Checkbox, Radio, Toggle, Toast, Modal |
| 3 | [SPRINT-03](./sprints/SPRINT-03-data-navigation.md) | ☐ A fazer | Pagination, Table, DataTable, Tabs, Dropdown |
| 4 | [SPRINT-04](./sprints/SPRINT-04-demo-tests-docs.md) | ☐ A fazer | Demo polida, Card/Divider/Container/Badge/Breadcrumb/IconButton, docs, **validação de produtividade**, CI |

---

## Tags de versão

Crie uma tag Git ao final de cada sprint, conforme padrão SemVer pré-1.0:

| Tag | Marco |
|---|---|
| `v0.1.0` | Fim do Sprint 1 — primeiros componentes utilizáveis |
| `v0.2.0` | Fim do Sprint 2 — formulários completos + overlays |
| `v0.3.0` | Fim do Sprint 3 — DataTable funcional |
| `v0.4.0` | Fim do Sprint 4 — protótipo completo, validação executada |
| `v1.0.0` | Pós-defesa, após ajustes do orientador/banca |

---

## Requisitos rastreados

Cada RF/RNF do TCC aparece em um ou mais sprints. Tabela de rastreabilidade:

| Requisito | Descrição (resumida) | Sprint(s) |
|---|---|---|
| RF01 | Instalação via Composer sem config adicional | 1 |
| RF02 | Sintaxe Blade com prefixo configurável | 1 |
| RF03 | wire:model nativo em forms | 1, 2 |
| RF04 | Exibição automática de erros | 1, 2 |
| RF05 | Customização via atributos | Todos |
| RF06 | Loading states automáticos em botões | 1 |
| RF07 | Modal/Dropdown via Alpine + Livewire | 2, 3 |
| RF08 | DataTable com ordenação/filtragem/paginação | 3 |
| RF09 | Slots nomeados | Todos |
| RF10 | Publicação de views | 1 (mecanismo), 4 (documentado) |
| RNF01 | Zero JS adicional | Todos (validar) |
| RNF02 | Laravel 10 e 11 | 4 (CI matrix) |
| RNF03 | Time-to-first-component < 5min | 4 (medir) |
| RNF04 | Sem CSS próprio | Todos |
| RNF05 | PSR-12 | Todos (Pint) |
| RNF06 | Cobertura ≥ 70% | 4 (medir) |
| RNF07 | ARIA + axe-core sem violações críticas | Todos (axe na demo) |

---

## Trabalhos futuros (fora do escopo desta versão)

Anotar aqui sugestões que surgirem durante o desenvolvimento, para entrar no Cap. 6.4 do TCC ou para versões pós-1.0:

- [ ] Autocomplete (Combobox com busca assíncrona).
- [ ] DatePicker (input de data com calendário).
- [ ] FileUpload com preview e progress.
- [ ] Editor de texto rico (provavelmente wrapper para Tiptap ou similar).
- [ ] Sistema de temas pré-configurados (clean / corporate / vibrant).
- [ ] CLI artisan para gerar componentes customizados a partir de templates.
- [ ] Modo "headless" (sem classes Tailwind, só estrutura) para projetos com design system próprio.
- [ ] Integração documentada com Filament e Volt.
- [ ] Componentes de gráficos (Chart, Sparkline) — via integração com Chart.js ou similar.

---

## Decisões arquiteturais (ADRs leves)

| Data | Decisão | Motivo |
|---|---|---|
| Iter 1 | Tailwind 3 puro, sem plugins | Compatibilidade ampla, evitar imposição de DaisyUI/forms |
| Iter 1 | Zero JS próprio | Diferenciação clara vs WireUI, RNF01 cumprido por construção |
| Iter 1 | Componentes anônimos por padrão | Performance, menor cerimônia |
| Iter 1 | Prefixo `livewindui` configurável | Evitar colisão de nomes no consumidor |
| Iter 2 | Toast via eventos globais Livewire | Desacopla emissor (qualquer Livewire) de exibidor (container único) |
| Iter 2 | Modal com eventos globais discriminados por `name` | Suporta múltiplos modais simultâneos na mesma página |
| Iter 3 | DataTable com classe PHP, mas DOM via slots | Composição via @scope, customização sem perder reatividade |
| Iter 3 | Tabs com modo client-only (default) e server-side (opt-in) | Performance no caso comum, lazy loading quando necessário |

Adicione novas linhas conforme tomar decisões durante o desenvolvimento.
