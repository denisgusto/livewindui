# Guia de Avaliação do Artefato — LivewindUI

Documento destinado à **banca examinadora** e a quem for revisar o Trabalho de Conclusão
de Curso associado a este repositório.

| | |
|---|---|
| **Trabalho** | LivewindUI: desenvolvimento de uma biblioteca de componentes reativos para a TALL Stack com foco em produtividade e experiência do desenvolvedor |
| **Autor** | Denis Augusto Carreira da Silva |
| **Curso** | Pós-Graduação em Sistemas de Informação — Faculdade Futura / Grupo Educacional Faveni |
| **Artefato** | Pacote Composer `denisgusto/livewindui` |
| **Licença** | MIT |

> A documentação de uso da biblioteca, voltada a desenvolvedores consumidores, está em
> [`README.md`](README.md) (em inglês). **Este documento é complementar** e existe para
> permitir que o avaliador verifique, por conta própria, cada afirmação quantitativa
> feita no texto do TCC.

---

## 1. O que é o artefato

A LivewindUI é uma biblioteca de componentes Blade para a TALL Stack (Tailwind CSS,
Alpine.js, Laravel e Livewire), distribuída como pacote Composer. Ela entrega componentes
de interface pré-configurados com reatividade Livewire nativa — vínculo de dados, exibição
automática de erros de validação e estados de carregamento — sem exigir que o projeto
consumidor execute qualquer etapa de compilação JavaScript.

Três decisões de arquitetura descritas no TCC são diretamente observáveis no código:

- **Colocation com classe PHP para todos os componentes** — cada componente ocupa uma
  pasta em `src/Components/`, reunindo classe e view.
- **Bundle mínimo servido** — a lógica de estado (toast, calendar, signature) vive em
  `dist/livewind.js`, versionado e servido por rota, e não por npm no consumidor.
- **Tema por tokens semânticos** — arquivo único em `resources/css/livewind.css`, com
  suporte a modo escuro sem classes condicionais nos componentes.

---

## 2. Requisitos de ambiente

Para **avaliar o artefato** (seções 4 e 5 deste guia):

| Requisito | Versão | Observação |
|---|---|---|
| PHP | 8.1 ou superior | com `ext-zip` e `ext-mbstring` |
| Composer | 2.x | |
| Node.js | 18 ou superior | **opcional** — apenas para reexecutar os testes de JS |
| Python | 3.8 ou superior | **opcional** — apenas para reproduzir a medição do Quadro 6 |

Para **usar a biblioteca** em um projeto (seção 3):

| Requisito | Versão |
|---|---|
| Laravel | 10, 11, 12 ou 13 |
| Livewire | 3.5+ ou 4.3+ |
| Tailwind CSS | 4.x |

---

## 3. Instalação em um projeto Laravel

Esta seção reproduz o procedimento de adoção descrito no TCC. São **quatro passos**.

### Passo 1 — Instalar o pacote

```bash
composer require denisgusto/livewindui
```

O Service Provider é registrado automaticamente por auto-discovery. Nenhum registro
manual é necessário.

### Passo 2 — Executar o instalador

```bash
php artisan livewind:install
```

O comando publica o tema e o arquivo de configuração e insere os imports necessários no
CSS da aplicação.

### Passo 3 — Conferir o CSS da aplicação

Caso prefira fazer manualmente, o `resources/css/app.css` deve conter:

```css
@import "tailwindcss";
@import "../../vendor/denisgusto/livewindui/resources/css/livewind.css";
@source "../../vendor/denisgusto/livewindui/src/Components";
```

> **Não existe `tailwind.config.js`.** A biblioteca tem como alvo o Tailwind CSS 4, que é
> configurado por CSS. A diretiva `@source` substitui o antigo array `content`.

### Passo 4 — Adicionar as diretivas ao layout

```blade
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewindAppearance  {{-- opcional: script anti-flash de modo escuro --}}
</head>
<body>
    {{ $slot }}
    @livewindScripts     {{-- runtime: bundle servido + container global de toasts --}}
</body>
</html>
```

`@livewindAppearance` é **opcional e intrusivo** — controla a classe `.dark` da página
inteira. Em aplicação que já gerencia tema, omita-a.

### Verificação

```blade
<x-livewind::button>Funcionou</x-livewind::button>
<x-livewind::input model="email" label="E-mail" />
```

### Plugins Alpine opcionais

Dois componentes usam diretivas que não integram o núcleo do Alpine embarcado pelo
Livewire. Registre o plugin apenas se usar o recurso correspondente:

| Componente / prop | Diretiva | Plugin |
|---|---|---|
| `<x-livewind::modal>` (confinamento de foco) | `x-trap` | `@alpinejs/focus` |
| `<x-livewind::input mask="...">` | `x-mask` | `@alpinejs/mask` |

---

## 4. Avaliação do artefato

```bash
git clone https://github.com/denisgusto/livewindui.git
cd livewindui
composer install
```

Os quatro comandos de verificação, todos executáveis sem configuração adicional:

```bash
vendor/bin/pest          # suíte de testes PHP
vendor/bin/pint --test   # conformidade PSR-12
composer analyse         # análise estática (PHPStan/Larastan, nível 5)
npm install && npm test  # testes da lógica JavaScript (requer Node)
```

---

## 5. Rastreabilidade — afirmações do TCC e como verificá-las

Cada linha associa uma afirmação quantitativa do texto ao comando que a comprova. Os
valores da coluna "resultado esperado" foram obtidos em 20/07/2026.

| # | Afirmação no TCC | Comando | Resultado esperado |
|---|---|---|---|
| 1 | 24 componentes implementados | `ls src/Components \| wc -l` | `24` |
| 2 | 160 testes e 405 asserções, integralmente aprovados | `vendor/bin/pest` | `Tests: 160 passed (405 assertions)` |
| 3 | Correspondência 1:1 entre componentes e arquivos de teste | `ls tests/Feature/Components \| wc -l` | `24` |
| 4 | Lógica JavaScript com testes próprios | `npm test` | `Test Files 3 passed · Tests 17 passed` |
| 5 | Conformidade PSR-12 (RNF05) | `vendor/bin/pint --test` | `{"tool":"pint","result":"passed"}` |
| 6 | Análise estática em nível 5 (RNF05) | `composer analyse` | `[OK] No errors` |
| 7 | Bundle JS ≤ 10 KB não minificado (RNF01) | `wc -c dist/livewind.js` | `3967` bytes |
| 8 | Compatibilidade Laravel 10 a 13 (RNF02) | `cat .github/workflows/*.yml` | matriz PHP 8.1–8.4 × Laravel 10/11/12/13 |
| 9 | Arquivo único de tema, sem CSS de componente (RNF04) | `ls resources/css/` | apenas `livewind.css` |
| 10 | Ausência de `tailwind.config.js` | `ls tailwind.config.js` | arquivo inexistente |
| 11 | Internacionalização en/pt_BR | `ls lang/*` | `en/` e `pt_BR/`, 3 domínios cada |
| 12 | Atributos ARIA nos componentes interativos (RNF07) | `grep -rl "aria-\\\|role=" src/Components/ \| wc -l` | `27` arquivos |
| 13 | 2.969 linhas PHP e 1.260 linhas Blade | `find src -name "*.php" \| xargs wc -l \| tail -1` | `2969` |
| 14 | Redução de 84,6% em linhas de template (Quadro 6) | `python3 validacao-produtividade/medir.py` | ver seção 6 |

### Componentes planejados e **não** implementados

O TCC declara abertamente que sete itens do escopo planejado ficaram de fora, remanejados
para trabalhos futuros. A ausência é verificável:

```bash
for c in Drawer Sidebar Grid ButtonGroup Autocomplete DatePicker FileUpload; do
  test -d "src/Components/$c" && echo "existe: $c" || echo "ausente: $c"
done
```

Todos devem retornar `ausente`.

---

## 6. Reprodução da medição de produtividade (Quadro 6)

O Quadro 6 do TCC compara o número de linhas de template necessárias para construir três
cenários de interface, em duas versões: implementação manual com Blade, Tailwind e
diretivas Livewire, e implementação com os componentes da biblioteca.

```bash
python3 validacao-produtividade/medir.py
```

Saída esperada:

```
Cenário                       Manual  LivewindUI   Redução
----------------------------------------------------------
Cenário 1 (Formulário)           120          11     90.8%
Cenário 2 (Listagem)              68          14     79.4%
Cenário 3 (Painel)                66          14     78.8%
----------------------------------------------------------
Total / Média                    254          39     84.6%
```

O script regenera os seis arquivos em `validacao-produtividade/` e recalcula a contagem
do zero, permitindo inspeção do código-fonte de cada cenário.

**Critérios de conclusão**, definidos antes da implementação e idênticos para as duas
versões de cada cenário:

- **Cenário 1 — formulário:** oito campos, cada qual com rótulo, vínculo reativo, estado
  visual de erro, mensagem de erro e atributos ARIA; botão de envio com indicação de
  carregamento.
- **Cenário 2 — listagem:** cabeçalhos ordenáveis, busca com atraso, filtro por seleção,
  estado vazio e paginação.
- **Cenário 3 — painel:** três cartões de métrica, janela modal acessível (rótulo, fecho
  por tecla e por sobreposição) e área de notificações.

**Métrica:** linhas não vazias e que não sejam comentário puro.

**Validação de funcionamento:** as três versões que utilizam a biblioteca foram submetidas
a renderização efetiva via `Blade::render()` durante a elaboração da medição, assegurando
que a comparação considera apenas código que de fato funciona. Esse cuidado corrigiu três
usos incorretos de API na primeira versão da medição.

---

## 7. Limitações declaradas

Registradas no próprio TCC e reproduzidas aqui para transparência da avaliação:

1. **A métrica é de concisão de código, não de tempo.** A medição de tempo de
   desenvolvimento foi deliberadamente excluída: o autor é o próprio desenvolvedor da
   biblioteca, e o viés de familiaridade não seria controlável nas condições do estudo.
2. **As duas implementações comparadas foram produzidas pelo autor**, que conhece
   previamente a estrutura gerada pelos componentes.
3. **No cenário 3**, a área de notificações da versão com a biblioteca é fornecida por
   diretiva de layout e, portanto, não é contabilizada no template — vantagem real de
   arquitetura que, ainda assim, amplia a diferença medida naquele cenário.
4. **Não há auditoria automatizada de acessibilidade.** A verificação do RNF07 limitou-se
   à inspeção estática da presença de atributos ARIA.
5. **Não há relatório de cobertura de testes.** O ambiente de desenvolvimento não dispõe
   de Xdebug ou PCOV; o critério do RNF06 foi definido como correspondência 1:1 entre
   componentes e arquivos de teste, verificável pelo item 3 da seção 5.

---

## 8. Estrutura do repositório

```
livewindui/
├── src/
│   ├── LivewindServiceProvider.php   # registro, diretivas, rota do bundle
│   ├── Components/<Nome>/            # classe PHP + view Blade (colocation)
│   ├── Concerns/                     # trait InteractsWithToasts
│   ├── Console/                      # comando livewind:install
│   └── Facades/                      # facade Livewind
├── resources/css/livewind.css        # tema: tokens semânticos, claro e escuro
├── js/                               # fonte do bundle (Alpine.data + testes Vitest)
├── dist/livewind.js                  # bundle compilado e versionado
├── lang/{en,pt_BR}/                  # traduções
├── tests/Feature/Components/         # um arquivo de teste por componente
├── validacao-produtividade/          # cenários e script do Quadro 6
├── config/livewind.php               # prefixo e padrões globais
└── README.md                         # documentação de uso (inglês)
```

---

## 9. Contato

Dúvidas sobre o artefato ou sobre a reprodução das medições:
**denisgusto@gmail.com** · https://github.com/denisgusto/livewindui
