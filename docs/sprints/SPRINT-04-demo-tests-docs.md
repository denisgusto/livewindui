# SPRINT-04 — App demo, testes, docs e validação

**Duração estimada:** 2 semanas
**Pré-requisito:** SPRINT-03 concluído (DataTable + Tabs + Dropdown + Pagination funcionando).
**Iteração do Quadro 5 do TCC:** 4

---

## Objetivo

Fechar o protótipo com qualidade de defesa: app demo polida (homepage, README do projeto, documentação inline dos componentes), testes finais com cobertura ≥ 70%, componentes complementares de baixa complexidade (Card, Divider, Container, Badge), e — o mais importante — **executar o protocolo de validação de produtividade dos três cenários** descrito em 3.7.2 do TCC, gerando os dados reais que substituirão os placeholders amarelos do Capítulo 5.

---

## Entregáveis

### 1. Componentes complementares (baixa complexidade)

- [ ] `card.blade.php` — wrapper com slots `header`, `footer`. Variantes: default, bordered, elevated.
- [ ] `divider.blade.php` — `<hr>` estilizado. Prop `label` opcional (centraliza texto).
- [ ] `container.blade.php` — wrapper com max-width responsivo. Props: `size` (sm, md, lg, xl, full).
- [ ] `badge.blade.php` — etiquetas. Variantes: success, info, warning, danger, neutral. Prop `dot` opcional (mostra um pontinho colorido).
- [ ] `breadcrumb.blade.php` + `breadcrumb-item.blade.php` — ARIA `nav aria-label="Breadcrumb"`, último item com `aria-current="page"`.
- [ ] `icon-button.blade.php` — variante de Button só para ícone, com `aria-label` obrigatório.

Para cada um: teste Pest mínimo (renderização + variantes + merge) + página `/components/<nome>` na demo.

### 2. App demo — polimento final

- [ ] **Homepage `/`** redesenhada:
  - Hero com nome da biblioteca + tagline.
  - Snippet de código mostrando "antes e depois" (manual vs LiveWindUI).
  - Grid de cards linkando para cada componente.
  - Link para `/contatos` como "veja em ação".
- [ ] **`/contatos` completa** (junção do que foi sendo construído):
  - Dashboard inicial com 4 cards de métricas (total, ativos, novos no mês, etc).
  - Tabela com DataTable completa (do Sprint 3).
  - Formulário com Tabs (Dados/Endereço/Observações) em Modal de criar/editar.
  - Toggle inline.
  - Dropdown de ações por linha.
  - Toast de sucesso em todas as ações.
  - Modal de confirmação de exclusão.
- [ ] **Página `/sobre`** com origem do projeto, link para o repositório, créditos.
- [ ] Layout `layouts/app.blade.php` com sidebar responsiva (Drawer no mobile) + topbar com avatar/dropdown.

### 3. README.md do projeto

Substituir o placeholder do Sprint 1 por um README completo:

- Logo/título.
- Tagline (uma frase).
- Badges (PHP version, Laravel version, license, tests passing).
- Quick install (3 comandos).
- Quick example (snippet visual).
- Lista de componentes com links para a documentação.
- Filosofia (zero JS próprio, Tailwind puro, DX-first).
- Requisitos.
- Contribuindo.
- Licença (MIT).

### 4. Documentação inline

Para cada componente Blade, adicionar comentário no topo do arquivo com:
- Uma frase do que faz.
- Lista de props.
- Snippet mínimo de uso.

Para classes PHP, PHPDoc completo em `class` e métodos públicos.

### 5. Testes — fechamento

- [ ] Cobrir buracos: rodar `vendor/bin/pest --coverage --min=70` e completar testes onde estiver abaixo.
- [ ] Teste de integração da app demo: pelo menos um teste que renderiza `/contatos` autenticado e verifica que a página carrega sem erro.
- [ ] Adicionar `phpunit.xml` ou `pest.xml` com configuração de coverage.

### 6. Pipeline CI básico

- [ ] `.github/workflows/ci.yml` rodando: `composer install` → `vendor/bin/pint --test` → `vendor/bin/pest`.
- [ ] Matrix com PHP 8.2 e 8.3, Laravel 10 e 11.

### 7. Validação de produtividade — o mais importante deste sprint

Esta é a etapa que **substitui os placeholders amarelos do Capítulo 5 do TCC** por dados reais. Seguir o protocolo de 3.7.2:

1. **Definir critérios de "pronto" por cenário** — escrever em `/validacao/criterios.md` antes de começar:
   - Cenário 1 (Formulário): X campos, validação client+server, botão de submit com loading, mensagens de erro inline, layout responsivo até 768px.
   - Cenário 2 (Listagem): tabela com Y colunas, busca, ordenação por Z colunas, filtro por categoria, paginação 10/página.
   - Cenário 3 (Painel): N cards de métrica, modal de detalhe ao clicar num card, toast de feedback em ação X.

2. **Implementar versão MANUAL** (sem LiveWindUI), do zero, num Laravel fresh:
   - Gravação de tela ligada (use OBS ou similar).
   - Cronometrar tempo total desde "git init" até "critérios pronto atingidos".
   - Contar linhas de código produtivas (sem em-branco/comentários, sem migrations/factories) via `cloc` ou script.

3. **Pausa de 24h.**

4. **Implementar versão LIVEWINDUI**, mesma especificação, num Laravel fresh + composer require LiveWindUI:
   - Gravação de tela.
   - Cronometrar.
   - Contar linhas.

5. **Repetir para os 3 cenários.**

6. **Consolidar resultados** em `/validacao/resultados.md`:
   - Tabela com linhas, tempo, redução percentual.
   - Comentários qualitativos.
   - Lições aprendidas.

7. **Atualizar o TCC**: abrir `TCC_LiveWindUI_Unificado.docx`, ir aos blocos amarelos do Cap. 5 e substituir pelos números reais. Re-renderizar/exportar para PDF.

### 8. Validações finais para o TCC

- [ ] **Time-to-first-component (RNF03)**: ambiente totalmente limpo (VM ou container), cronometrar desde `composer create-project laravel/laravel app` até ver um Button funcionando na página. Documentar passo a passo e duração em `/validacao/ttfc.md`.
- [ ] **Cobertura de testes (RNF06)**: rodar `vendor/bin/pest --coverage` e anexar relatório.
- [ ] **Acessibilidade (RNF07)**: rodar axe-core em `/contatos`, exportar JSON, anexar.
- [ ] **Bundle JS (RNF01)**: `npm run build` na demo, `du -h public/build/assets/*.js`, confirmar tamanho idêntico a um projeto Laravel+Livewire sem a biblioteca.

### 9. Apêndices para o TCC

- [ ] `/validacao/cenario1-manual/` — código completo do cenário 1 versão manual.
- [ ] `/validacao/cenario1-livewindui/` — código completo do cenário 1 versão LiveWindUI.
- [ ] (idem para cenários 2 e 3).
- [ ] Cada pasta com README.md explicando como rodar.
- [ ] Link público no GitHub para auditoria.

---

## Critérios de aceitação

1. App demo "vendável": home bonita, /contatos funcional e fluida, /sobre presente.
2. README do projeto pronto para publicar no GitHub.
3. Coverage ≥ 70%, CI passando.
4. Validação de produtividade executada, gravações armazenadas, dados consolidados.
5. **Capítulo 5 do TCC atualizado com dados reais** — nenhum highlight amarelo restante (exceto os intencionalmente educativos sobre limitações).
6. Apêndices A/B/C do TCC preenchidos com os 6 códigos (3 cenários × 2 versões).
7. Bundle JS validado, time-to-first-component cronometrado.

---

## Sequência sugerida

1. **Componentes complementares primeiro** (Card, Divider, Container, Badge, Breadcrumb, IconButton) — paralelizáveis, rápidos.
2. Polimento da `/contatos` + nova home.
3. README do projeto.
4. **Validação de produtividade — bloco contínuo de execução**, pelo menos 1 semana dedicada, sem multitarefa.
5. Atualização do TCC com os dados reais.
6. CI/CD.
7. Empacotamento final, push para GitHub, ajustes finais de README.

---

## Notas e armadilhas

- **Resista à tentação** de adicionar mais componentes neste sprint. O que está fora do escopo entra como "trabalho futuro" no Cap. 6.4 do TCC.
- A **validação de produtividade é o ponto mais sensível academicamente** — qualquer atalho aqui mina a credibilidade do trabalho inteiro. Não pule a pausa de 24h. Não "estime" o tempo — cronometre. Não "estime" linhas — conte.
- Se os números reais da validação diferirem do exemplo placeholder (73%), **reescreva os parágrafos discursivos do Cap. 5.5 e 6.1** para refletir o achado real. Não force.
- Se algum RNF não for atendido, **declare honestamente** no Quadro 13 ("Atendido parcialmente" / "Não atendido") e justifique. Banca valoriza honestidade > números bonitos.

---

## Saída de revisão (sprint 4 = saída final)

- [ ] Total de componentes implementados: ___ .
- [ ] Total de testes Pest: ___ . Cobertura: ___%.
- [ ] Time-to-first-component medido: ___ min ___ seg.
- [ ] axe-core /contatos: ___ violações críticas (precisa ser 0).
- [ ] Bundle JS próprio: ___ KB (precisa ser 0).
- [ ] **Resultados de produtividade**:
  - Cenário 1: ___ → ___ linhas (___% redução); ___ → ___ tempo.
  - Cenário 2: ___ → ___ linhas (___% redução); ___ → ___ tempo.
  - Cenário 3: ___ → ___ linhas (___% redução); ___ → ___ tempo.
  - Média: ___% redução em linhas; ___% redução em tempo.
- [ ] Cap. 5 do TCC atualizado com os dados acima — sem placeholders amarelos restantes.
- [ ] Apêndices A/B/C do TCC montados.
- [ ] Repositório público publicado em https://github.com/___/livewindui.

**Fim do desenvolvimento.** Próxima etapa: revisão pelo orientador, ajustes finais e entrega.
