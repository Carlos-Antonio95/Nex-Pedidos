# Nex-Pedidos

Sistema de Gerenciamento de Pedidos de Restaurante — projeto acadêmico.

Permite cadastrar os itens do cardápio, abrir pedidos por mesa ou cliente,
adicionar itens, calcular o total e acompanhar o status de cada pedido.

**Tecnologias:** PHP puro (sem framework e sem Composer), SQLite via PDO,
HTML e CSS (sem JavaScript). Arquitetura MVC simples.

## Como rodar

1. Instale o PHP (7.4 ou superior) com a extensão `pdo_sqlite` ativa.
   Para conferir: `php -m` deve listar `pdo_sqlite`.
   No Windows, se não aparecer, descomente a linha `extension=pdo_sqlite` no `php.ini`.
2. Na raiz do projeto, execute:

   ```
   php -S localhost:8000 -t public
   ```

   A opção `-t public` faz a pasta `public/` ser a raiz do site. Assim só os
   controllers e os arquivos de `assets/` ficam acessíveis pelo navegador;
   `config/`, `models/`, `views/` e o banco não podem ser abertos por URL.

3. Acesse <http://localhost:8000/cardapio.php> no navegador.

O banco `database/pedidos.sqlite` é criado automaticamente na primeira
execução, já com as tabelas e alguns itens de exemplo no cardápio.
Para começar do zero, basta apagar esse arquivo.

## Estrutura de pastas

```
public/                    Raiz do servidor (única pasta acessível pelo navegador)
  index.php                Página inicial (controller)
  cardapio.php             Controller do cardápio (Sprint 1)
  pedidos.php              Controller da lista de pedidos (Sprints 2 e 3)
  pedido.php               Controller de um pedido específico (Sprints 2 e 3)
  assets/css/              Folhas de estilo
config/database.php        Conexão PDO, criação das tabelas e dados de exemplo
models/ItemCardapio.php
models/Pedido.php
views/layout/              Cabeçalho, rodapé e partes comuns das telas
views/cardapio/            Telas do cardápio (lista.php e form.php)
views/pedidos/             Telas dos pedidos
database/                  Arquivo .sqlite (gerado, não vai para o Git)
```

As views ficam fora de `public/`, mas são incluídas pelos controllers. Por isso,
nos links e no CSS use caminhos relativos a `public/`, por exemplo
`<link rel="stylesheet" href="assets/css/estilo.css">` e `href="cardapio.php"`.

## Requisitos por sprint

| Sprint | Requisitos                     | Onde                                 |
|--------|--------------------------------|--------------------------------------|
| 1      | RF001, RF002, RN001            | `public/cardapio.php`, `models/ItemCardapio.php` |
| 2      | RF003, RF004, RF005, RN002     | `public/pedidos.php`, `public/pedido.php`, `models/Pedido.php` |
| 3      | RF006, RF007, RF008, RN003, RN004 | `public/pedidos.php`, `public/pedido.php`, `models/Pedido.php` |

## Contrato com o front-end

### public/cardapio.php

O controller faz `include` de uma destas views:

- `views/cardapio/lista.php` — listagem dos itens
- `views/cardapio/form.php` — formulário de cadastro/edição

#### Variáveis que as views recebem

As quatro variáveis estão **sempre definidas**, nas duas views.

| Variável    | Tipo   | Conteúdo |
|-------------|--------|----------|
| `$itens`    | array  | Lista de itens (usada em `lista.php`). Cada item tem as chaves `id`, `nome`, `descricao`, `preco`, `categoria`. Vem ordenada por categoria e nome. |
| `$item`     | array  | Item do formulário (usado em `form.php`), com as mesmas chaves. No cadastro, todas vêm vazias. Se a validação falhar, vem com o que o usuário digitou. |
| `$erros`    | array  | Mensagens de erro (strings). Vazio quando não há erro. Pode aparecer nas duas views. |
| `$mensagem` | string | Mensagem de sucesso (ex.: "Item cadastrado com sucesso."). Vazia quando não há. Aparece na `lista.php`. |

Para saber se o formulário é de cadastro ou edição: `!empty($item['id'])` significa edição.

Lembre de escapar os valores ao exibir: `htmlspecialchars($item['nome'])`.

#### Links (GET)

| Link                               | O que mostra              |
|------------------------------------|---------------------------|
| `cardapio.php`                     | Listagem                  |
| `cardapio.php?acao=cadastrar`      | Formulário vazio          |
| `cardapio.php?acao=editar&id=ID`   | Formulário preenchido     |

#### Formulários (POST para `cardapio.php`)

Cadastrar / editar (`form.php`):

| Campo       | Tipo de input             | Observação |
|-------------|---------------------------|------------|
| `acao`      | hidden                    | `cadastrar` ou `editar` |
| `id`        | hidden                    | Só na edição (`$item['id']`) |
| `nome`      | text                      | Obrigatório |
| `descricao` | textarea                  | Opcional |
| `preco`     | text                      | Obrigatório, maior que zero; aceita `8,50` ou `8.50` |
| `categoria` | text (ou select)          | Obrigatório |

Excluir (botão na `lista.php`, um pequeno form por item — **sempre POST**, nunca link):

| Campo  | Tipo   | Valor |
|--------|--------|-------|
| `acao` | hidden | `excluir` |
| `id`   | hidden | ID do item |

Exemplo:

```html
<form method="post" action="cardapio.php">
    <input type="hidden" name="acao" value="excluir">
    <input type="hidden" name="id" value="<?= $i['id'] ?>">
    <button type="submit">Excluir</button>
</form>
```

Depois de salvar ou excluir com sucesso, o controller redireciona para
`cardapio.php?sucesso=...` e a listagem recebe a `$mensagem` correspondente.
