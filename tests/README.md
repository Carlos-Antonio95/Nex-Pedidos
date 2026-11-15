# Testes automatizados — Nex-Pedidos

Testes de interface com **Robot Framework** + **SeleniumLibrary** (navegador Chrome),
cobrindo os 20 casos de teste do documento (positivo e negativo do RF001 ao RF010).

## Estrutura

```
tests/
├── requirements.txt        Versões fixas do Robot Framework e da SeleniumLibrary
├── Pages/                  SOMENTE XPaths (seção *** Variables ***), um arquivo por tela
├── Keywords/               As ações (keywords) de cada tela, usando os XPaths das Pages
│   ├── Base.robot          Abrir/fechar o navegador, login do admin e keywords de apoio
│   └── *_Steps.robot       Uma por tela
└── CenarioXX.robot         Os casos de teste (Gherkin), sem nenhum XPath
```

Como as camadas se conectam:

```
CenarioXX.robot  →  Keywords/*_Steps.robot  →  Pages/*_Page.robot
      (o quê)              (como)                  (onde, XPath)
```

## 1. Instalação (uma vez)

Pré-requisitos: **Python 3**, **pip** e **Google Chrome** instalados.
O driver do Chrome é baixado automaticamente pelo Selenium na primeira execução
(precisa de internet nessa hora).

Na raiz do projeto:

```
pip install -r tests/requirements.txt
```

Para conferir: `robot --version`.

## 2. Subir o sistema num banco de teste limpo

Os testes contam com os **dados de exemplo** de um banco novo (veja o README principal)
e alteram dados (criam itens, pedidos e usuários). Por isso, rode sempre num banco
separado e **apague-o antes de cada rodada completa**.

Em um terminal, na raiz do projeto (PowerShell):

```
Remove-Item database/teste.sqlite -ErrorAction Ignore; $env:DB_PATH="database/teste.sqlite"; php -S localhost:8000 -t public
```

Linux/Mac:

```
rm -f database/teste.sqlite; DB_PATH=database/teste.sqlite php -S localhost:8000 -t public
```

Deixe esse terminal aberto. O banco é criado com o usuário `admin@nexpedidos.com` / `admin123`,
que o `Base.robot` usa para entrar no sistema.

## 3. Rodar os testes

Em outro terminal, na raiz do projeto:

| O quê | Comando |
|-------|---------|
| Todos os testes | `robot -d results tests` |
| Só a suíte smoke | `robot -d results -i smoke tests` |
| Só a suíte sanity | `robot -d results -i sanity tests` |
| Só a suíte regression | `robot -d results -i regression tests` |
| Só um RF | `robot -d results -i RF003 tests` |
| Só um arquivo | `robot -d results tests/Cenario03.robot` |
| Conferir sintaxe sem abrir o navegador | `robot --dryrun -d results tests` |

O relatório fica em `results/report.html` (a pasta `results/` não vai para o Git).

Opções úteis:

- Sem abrir a janela do Chrome: `robot -d results -v BROWSER:headlesschrome tests`
- Sistema em outra porta: `robot -d results -v URL:http://localhost:8001 tests`

### Ordem e dependências entre cenários

O Robot roda os arquivos em ordem alfabética (Cenario01, Cenario02, …), e isso importa:

- **Cenario07 (RF007-N)** espera que **não exista** pedido "Entregue". Ele precisa rodar
  antes do **Cenario08 (RF008-N)**, que transforma o pedido "Pronto" em "Entregue".
- **Cenario08 (RF008-N)** usa o único pedido "Pronto" dos dados de exemplo.
  Para repetir, recrie o banco de teste (passo 2).
- **Cenario10 (RF010)** usa o usuário `maria@email.com`, criado no **Cenario09 (RF009-P)**.
- Rodando só um arquivo ou só uma suíte, garanta que os dados necessários existem.

### Suítes (tags)

| Suíte | Casos |
|-------|-------|
| smoke | RF001-P, RF003-P, RF004-P, RF009-P, RF010-P |
| sanity | RF009-P, RF009-N, RF010-P, RF010-N |
| regression | todos os 20 |

Cada caso também tem a tag do RF (ex.: `RF004`) e `positivo` ou `negativo`.

## 4. Quem é dono de cada arquivo

| Pessoa | RFs | Arquivos (e blocos) |
|--------|-----|---------------------|
| Pessoa 1 | RF001, RF002 | `Cenario01.robot`, `Cenario02.robot`, `Keywords/Cardapio_Steps.robot` |
| Pessoa 2 | RF003, RF004 | `Cenario03.robot`, `Cenario04.robot`, blocos "Pessoa 2" de `Keywords/Pedidos_Steps.robot` e `Keywords/Pedido_Detalhe_Steps.robot` |
| Pessoa 3 | RF005, RF006 | `Cenario05.robot`, `Cenario06.robot`, blocos "Pessoa 3" de `Keywords/Pedido_Detalhe_Steps.robot` |
| Pessoa 4 | RF007, RF008 | `Cenario07.robot`, `Cenario08.robot`, blocos "Pessoa 4" de `Keywords/Pedidos_Steps.robot` e `Keywords/Pedido_Detalhe_Steps.robot` |
| Pessoa 5 | RF009, RF010 | `Cenario09.robot`, `Cenario10.robot`, `Keywords/Cadastro_Steps.robot`, `Keywords/Login_Steps.robot` |
| Pessoa 6 | Base e comuns | `Keywords/Base.robot`, `Pages/*` (mapeamento), `requirements.txt`, blocos "Comum" de `Keywords/Pedidos_Steps.robot` e `Keywords/Pedido_Detalhe_Steps.robot` |

Os blocos "Comum" (Pessoa 6) têm keywords que mais de uma pessoa usa:
"Abrir o pedido com status" (já pronta), "Abrir um novo pedido para a mesa",
"Adicionar item ao pedido", as mensagens de sucesso/erro do pedido e o status do pedido.
Quem depende delas (Pessoas 2, 3 e 4) pode chamá-las, mas não editá-las.

## 5. Como preencher a sua parte

1. Nas suas keywords (`Keywords/`), troque a linha `Fail    TODO: ...` pela implementação.
   A dica diz qual variável da Page usar.
2. Nos seus cenários, apague a linha `Skip    Pendente de automação` e tire o `# `
   dos passos (deixe as linhas `# Obs.:` como comentário).
3. Rode só o seu arquivo até passar, por exemplo: `robot -d results tests/Cenario03.robot`.
4. Antes do Pull Request, rode `robot --dryrun -d results tests` para garantir que
   nada quebrou para os outros.

Para elementos de um item específico, as Pages têm XPaths **modelo** com `{}`.
Use a keyword `Montar XPath` do `Base.robot`:

```robotframework
${botao}=    Montar XPath    ${DETALHE_BOTAO_REMOVER_1_DO_ITEM}    Hambúrguer
Click Button    ${botao}
```

Para os botões com janela de confirmação ("Excluir" e "Cancelar pedido"), clique
e depois chame `Confirmar a janela do navegador`.

## 6. Regras do grupo

- **Nada de XPath fora de `Pages/`.** Cenários e Keywords só usam as variáveis das Pages.
  Precisa de um XPath novo? Peça à Pessoa 6 (ou adicione na Page da sua tela e avise no PR).
- XPaths estáveis: por `id`/`name` ou pelo texto exato do botão (`normalize-space()`).
  Nada de caminho absoluto (`/html/body/div[2]/...`).
- **Cada um mexe só nos seus arquivos e nos seus blocos** (`# ===== Pessoa X — RF00Y =====`).
- **Uma branch por pessoa**, a partir da `main`:
  `testes/rf001-rf002`, `testes/rf003-rf004`, `testes/rf005-rf006`,
  `testes/rf007-rf008`, `testes/rf009-rf010` e `testes/base` (Pessoa 6).
- A junção na `main` é feita **por Pull Request**, revisado por outra pessoa do grupo.
- Código e comentários em português.
