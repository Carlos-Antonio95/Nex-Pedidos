*** Settings ***
Documentation    RF006 — Alterar status do pedido (Pessoa 3).
...              Sem XPath aqui: os passos usam as keywords de Keywords/.
Resource         Keywords/Base.robot
Resource         Keywords/Pedidos_Steps.robot
Resource         Keywords/Pedido_Detalhe_Steps.robot
Test Setup       Abrir o sistema logado
Test Teardown    Fechar o sistema


*** Test Cases ***
RF006-P - avançar o pedido para a próxima etapa
    [Documentation]    Dado que existe um pedido com status "Recebido"
    ...                Quando altero o status para "Em preparo"
    ...                Então o sistema deve atualizar o status do pedido para "Em preparo"
    [Tags]    RF006    positivo    smoke    regression
    Abrir o pedido com status    Recebido
    Verificar o status do pedido    Recebido
    Avançar o status para    Em preparo
    Verificar o status do pedido    Em preparo

RF006-N - pular etapa do fluxo
    [Documentation]    Dado que existe um pedido com status "Recebido"
    ...                Quando tento alterar diretamente o status para "Pronto"
    ...                Então o sistema deve impedir a alteração
    ...                E deve manter o status "Recebido"
    [Tags]    RF006    negativo    regression
    Abrir o pedido com status    Recebido
    Verificar o status do pedido    Recebido
    Verificar que a única etapa oferecida é    Em preparo
    Verificar que a etapa não é oferecida    Pronto
    Verificar o status do pedido    Recebido