*** Settings ***
Documentation    RF003 — Abrir pedido (Pessoa 2).
...              Sem XPath aqui: os passos usam as keywords de Keywords/.
Resource         Keywords/Base.robot
Resource         Keywords/Pedidos_Steps.robot
Test Setup       Abrir o sistema logado
Test Teardown    Fechar o sistema


*** Test Cases ***
RF003-P - abrir pedido vinculado a uma mesa
    [Documentation]    Dado que estou na tela de pedidos
    ...                E informei o número da mesa "05"
    ...                Quando seleciono a opção "Abrir pedido"
    ...                Então o sistema deve criar um novo pedido
    ...                E deve exibir o pedido vinculado à mesa "05"
    [Tags]    RF003    positivo    smoke    regression
    # Obs.: Depois de abrir, o sistema vai para o detalhe do pedido e mostra "Mesa 05".
    Acessar a tela de pedidos
    Informar a mesa do pedido    05
    Clicar em Abrir pedido
    Verificar que o pedido foi aberto para    Mesa 05

RF003-N - abrir pedido sem identificação
    [Documentation]    Dado que estou na tela de pedidos
    ...                E não informei número de mesa nem nome do cliente
    ...                Quando seleciono a opção "Abrir pedido"
    ...                Então o sistema deve impedir a abertura do pedido
    ...                E deve solicitar a identificação da mesa ou do cliente
    [Tags]    RF003    negativo    regression
    Acessar a tela de pedidos
    Deixar mesa e cliente em branco
    Clicar em Abrir pedido
    Verificar mensagem de erro da lista de pedidos    Informe o número da mesa ou o nome do cliente.
