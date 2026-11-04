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

3. Acesse <http://localhost:8000> no navegador (abre a lista de pedidos).
   O cardápio fica em <http://localhost:8000/cardapio.php>.

O banco `database/pedidos.sqlite` é criado automaticamente na primeira
execução, já com as tabelas e os dados de exemplo abaixo.
Para começar do zero, basta apagar esse arquivo.

### Dados de exemplo

Cardápio:

| Item            | Categoria | Preço    |
|-----------------|-----------|----------|
| Suco de laranja | Bebidas   | R$ 8,50  |
| Hambúrguer      | Lanches   | R$ 20,00 |
| Suco            | Bebidas   | R$ 8,00  |

Pedidos:

| Pedido | Para        | Status     | Itens                          | Total    |
|--------|-------------|------------|--------------------------------|----------|
| #1     | Mesa 1      | Recebido   | 1 Hambúrguer + 1 Suco de laranja | R$ 28,50 |
| #2     | Mesa 2      | Em preparo | 2 Hambúrguer                   | R$ 40,00 |
| #3     | Cliente Ana | Pronto     | 1 Hambúrguer + 1 Suco          | R$ 28,00 |
| #4     | Cliente Bruno | Cancelado | 1 Hambúrguer                  | R$ 20,00 |

Não há pedido "Entregue" de propósito: o cenário de teste do filtro por status
(RF007) espera que um banco novo não tenha pedidos entregues.

Para testar o cancelamento de um pedido entregue (RF008), abra o pedido #3
("Pronto"), avance o status para "Entregue" e depois tente cancelá-lo: o
sistema deve mostrar "Não é possível cancelar um pedido já entregue.".

## Testes automatizados

Para não misturar os dados de teste com o banco do dia a dia, o caminho do
banco pode ser trocado pela variável de ambiente `DB_PATH`. Se ela não estiver
definida, o sistema usa `database/pedidos.sqlite`.

Windows (PowerShell):

```
$env:DB_PATH="database/teste.sqlite"; php -S localhost:8001 -t public
```

Linux/Mac:

```
DB_PATH=database/teste.sqlite php -S localhost:8001 -t public
```

- Caminhos relativos são contados a partir da **raiz do projeto**
  (não importa de qual pasta o comando foi executado). Caminhos absolutos
  também funcionam.
- Se o arquivo não existir, ele é criado na hora, com as tabelas e os dados de
  exemplo. Para repetir os testes do zero, apague o arquivo de teste antes.
- No PowerShell, a variável continua valendo até fechar o terminal. Para voltar
  ao banco normal: `Remove-Item Env:DB_PATH`.
- O `.gitignore` ignora qualquer `database/*.sqlite`, então o banco de teste
  não vai para o Git.

## Estrutura de pastas

```
public/                    Raiz do servidor (única pasta acessível pelo navegador)
  index.php                Página inicial (redireciona para pedidos.php)
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

| Sprint | Requisito | Descrição | Onde | Back-end |
|--------|-----------|-----------|------|----------|
| 1 | RF001 / RN001 | Cadastrar item do cardápio (preço maior que zero) | `public/cardapio.php`, `ItemCardapio::cadastrar()` | Pronto |
| 1 | RF002 | Editar e excluir item do cardápio | `public/cardapio.php`, `ItemCardapio::editar()` / `excluir()` | Pronto |
| 2 | RF003 | Abrir pedido por mesa ou cliente | `public/pedidos.php`, `Pedido::abrir()` | Pronto |
| 2 | RF004 / RN002 | Adicionar e remover itens (quantidade inteira maior que zero) | `public/pedido.php`, `Pedido::adicionarItem()` / `removerItem()` | Pronto |
| 2 | RF005 | Calcular o total do pedido | `public/pedido.php`, `Pedido::calcularTotal()` | Pronto |
| 3 | RF006 / RN003 | Alterar status seguindo a sequência, sem pular nem voltar | `public/pedido.php`, `Pedido::alterarStatus()` / `proximoStatus()` | Pronto |
| 3 | RF007 | Listar pedidos com filtro por status | `public/pedidos.php`, `Pedido::listar()` | Pronto |
| 3 | RF008 / RN004 | Cancelar pedido (exceto se já entregue) | `public/pedido.php`, `Pedido::cancelar()` | Pronto |

Sequência de status (RN003): **Recebido → Em preparo → Pronto → Entregue**.
"Cancelado" pode ser aplicado em qualquer etapa antes de "Entregue" (RN004).
Pedidos entregues ou cancelados não aceitam mais alterações.

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

### public/pedidos.php

O controller faz `include` de `views/pedidos/lista.php`, que mostra a
lista de pedidos, o filtro por status e o formulário para abrir um novo pedido.

#### Links (GET)

| Link                             | O que mostra                          |
|----------------------------------|---------------------------------------|
| `pedidos.php`                    | Todos os pedidos                      |
| `pedidos.php?status=Em+preparo`  | Só os pedidos com o status informado  |

#### Variáveis que a view recebe

Todas estão **sempre definidas**.

| Variável        | Tipo   | Conteúdo |
|-----------------|--------|----------|
| `$pedidos`      | array  | Lista de pedidos, do mais recente para o mais antigo. Cada um tem `id`, `mesa`, `cliente`, `status`, `criado_em` e `total` (float). |
| `$statusFiltro` | string | Status escolhido no filtro, ou vazio quando lista todos. Use para deixar a opção marcada no `<select>`. |
| `$listaStatus`  | array  | Os 5 status, na ordem: Recebido, Em preparo, Pronto, Entregue, Cancelado. Use para montar o `<select>` do filtro. |
| `$mesa`         | string | Valor digitado no campo mesa. Vazio, ou o que o usuário digitou se a abertura falhar. |
| `$cliente`      | string | Valor digitado no campo cliente. Mesmo comportamento de `$mesa`. |
| `$erros`        | array  | Mensagens de erro (strings). Vazio quando não há erro. Ex.: "Status inválido." se o filtro vier com um status que não existe. |
| `$mensagem`     | string | "Nenhum pedido encontrado." quando a lista vem vazia. Vazia nos outros casos. |

`mesa` e `cliente` de cada pedido podem vir `null`.

#### Filtro por status (GET para `pedidos.php`)

| Campo    | Tipo de input | Observação |
|----------|---------------|------------|
| `status` | select        | Vazio = todos os pedidos |

Exemplo:

```html
<form method="get" action="pedidos.php">
    <select name="status">
        <option value="">Todos</option>
        <?php foreach ($listaStatus as $s): ?>
            <option value="<?= htmlspecialchars($s) ?>" <?= $s === $statusFiltro ? 'selected' : '' ?>>
                <?= htmlspecialchars($s) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filtrar</button>
</form>
```

#### Formulário "Abrir pedido" (POST para `pedidos.php`)

| Campo     | Tipo de input  | Observação |
|-----------|----------------|------------|
| `acao`    | hidden         | `abrir` |
| `mesa`    | text ou number | Inteiro maior que zero. Opcional se o cliente for informado |
| `cliente` | text           | Opcional se a mesa for informada |

É preciso informar a mesa **ou** o cliente (pode ser os dois). Se der certo,
o controller redireciona para `pedido.php?id=ID&sucesso=aberto`.

### public/pedido.php

O controller faz `include` de `views/pedidos/detalhe.php`, que mostra um
pedido, seus itens, o total, os formulários para adicionar e remover itens
e os botões de avançar o status e cancelar.

#### Link (GET)

| Link                | O que mostra                |
|---------------------|-----------------------------|
| `pedido.php?id=ID`  | Detalhe do pedido           |

#### Variáveis que a view recebe

Todas estão **sempre definidas**.

| Variável         | Tipo         | Conteúdo |
|------------------|--------------|----------|
| `$pedido`        | array ou null | Dados do pedido: `id`, `mesa`, `cliente`, `status`, `criado_em`. É `null` quando o pedido não existe (nesse caso `$erros` traz "Pedido não encontrado."). |
| `$itensPedido`   | array        | Itens do pedido. Cada um tem `item_id`, `nome`, `quantidade`, `preco_unitario`, `subtotal`. Vazio se o pedido não tem itens. |
| `$total`         | float        | Total do pedido (soma dos subtotais). `0.0` se não houver itens. |
| `$itensCardapio` | array        | Itens do cardápio para o `<select>` (mesmas chaves de `$itens` do cardápio). |
| `$proximoStatus` | string ou null | Próxima etapa permitida (ex.: "Em preparo" quando o pedido está "Recebido"). `null` se o pedido está Entregue, Cancelado ou não existe. |
| `$erros`         | array        | Mensagens de erro (strings). Vazio quando não há erro. |
| `$mensagem`      | string       | Mensagem de sucesso (ex.: "Item adicionado ao pedido."). Vazia quando não há. |

A view deve testar `if ($pedido === null)` antes de mostrar os dados do pedido.

Para exibir valores em reais: `number_format($total, 2, ',', '.')` mostra `48,00`.

`mesa` e `cliente` podem vir `null` (o pedido tem um ou outro, ou os dois).

#### Formulários (POST para `pedido.php`)

Adicionar item e remover item usam os mesmos campos; só muda a `acao`.

| Campo        | Tipo de input | Observação |
|--------------|---------------|------------|
| `acao`       | hidden        | `adicionar` ou `remover` |
| `pedido_id`  | hidden        | `$pedido['id']` |
| `item_id`    | select (adicionar) / hidden (remover) | ID do item do cardápio |
| `quantidade` | number        | Inteiro maior que zero |

Exemplo de botão de remover uma unidade, para cada item da tabela:

```html
<form method="post" action="pedido.php">
    <input type="hidden" name="acao" value="remover">
    <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
    <input type="hidden" name="item_id" value="<?= $ip['item_id'] ?>">
    <input type="hidden" name="quantidade" value="1">
    <button type="submit">Remover 1</button>
</form>
```

Avançar status (RF006) — mostre **só** o botão da próxima etapa:

| Campo       | Tipo de input | Observação |
|-------------|---------------|------------|
| `acao`      | hidden        | `status` |
| `pedido_id` | hidden        | `$pedido['id']` |
| `status`    | hidden        | `$proximoStatus` |

```html
<?php if ($proximoStatus !== null): ?>
    <form method="post" action="pedido.php">
        <input type="hidden" name="acao" value="status">
        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
        <input type="hidden" name="status" value="<?= htmlspecialchars($proximoStatus) ?>">
        <button type="submit">Marcar como <?= htmlspecialchars($proximoStatus) ?></button>
    </form>
<?php endif; ?>
```

Cancelar pedido (RF008):

| Campo       | Tipo de input | Observação |
|-------------|---------------|------------|
| `acao`      | hidden        | `cancelar` |
| `pedido_id` | hidden        | `$pedido['id']` |

Dica: o botão de cancelar só faz sentido quando `$proximoStatus !== null`
(ou seja, o pedido ainda não foi entregue nem cancelado).

#### Respostas

Se der certo, o controller redireciona para `pedido.php?id=ID&sucesso=...`
e a view recebe a `$mensagem` correspondente:

| `sucesso`    | `$mensagem` |
|--------------|-------------|
| `aberto`     | Pedido aberto com sucesso. |
| `adicionado` | Item adicionado ao pedido. |
| `removido`   | Item removido do pedido. |
| `status`     | Status do pedido atualizado. |
| `cancelado`  | Pedido cancelado. |

Se der erro, a view é exibida com `$erros` e com os dados atuais do pedido
(nada é alterado). Pedidos com status "Entregue" ou "Cancelado" não aceitam
mais alterações nos itens nem no status.
