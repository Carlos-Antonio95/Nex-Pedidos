*** Settings ***
Documentation    RF005 — Calcular total do pedido (Pessoa 3).
...              Sem XPath aqui: os passos usam as keywords de Keywords/.
Resource         Keywords/Base.robot
Resource         Keywords/Pedidos_Steps.robot
Resource         Keywords/Pedido_Detalhe_Steps.robot
Test Setup       Abrir o sistema logado
Test Teardown    Fechar o sistema


*** Test Cases ***
RF005-P - calcular total conforme itens e quantidades
    [Documentation]    Dado que existe um pedido aberto
    ...                E o item "Hambúrguer" custa R$ 20,00 e o item "Suco" custa R$ 8,00
    ...                Quando adiciono 2 hambúrgueres e 1 suco ao pedido
    ...                Então o sistema deve apresentar o total de R$ 48,00
    [Tags]    RF005    positivo    smoke    regression
    Abrir um novo pedido para a mesa    10
    Adicionar item ao pedido    Hambúrguer    2
    Verificar mensagem de sucesso do pedido    Item adicionado ao pedido.
    Adicionar item ao pedido    Suco    1
    Verificar mensagem de sucesso do pedido    Item adicionado ao pedido.
    Verificar o total do pedido    R$ 48,00

RF005-N - não calcular com quantidade inválida
    [Documentation]    Dado que existe um pedido aberto
    ...                Quando tento incluir 0 unidades de um item com preço R$ 20,00
    ...                Então o sistema deve rejeitar a quantidade
    ...                E o total do pedido não deve ser alterado
    [Tags]    RF005    negativo    regression
    Abrir um novo pedido para a mesa    11
    ${total_antes}=    Pegar o total do pedido
    Adicionar item ao pedido    Hambúrguer    0
    Verificar mensagem de erro do pedido    A quantidade deve ser um número inteiro maior que zero.
    Verificar o total do pedido    ${total_antes}