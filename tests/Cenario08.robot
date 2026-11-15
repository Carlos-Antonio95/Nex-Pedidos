*** Settings ***
Documentation    RF008 — Cancelar pedido (Pessoa 4).
...              Sem XPath aqui: os passos usam as keywords de Keywords/.
...              "Abrir o pedido com status" (bloco Comum de Pedidos_Steps) já está pronta.
Resource         Keywords/Base.robot
Resource         Keywords/Pedidos_Steps.robot
Resource         Keywords/Pedido_Detalhe_Steps.robot
Test Setup       Abrir o sistema logado
Test Teardown    Fechar o sistema


*** Test Cases ***
RF008-P - cancelar pedido não entregue
    [Documentation]    Dado que existe um pedido com status "Em preparo"
    ...                Quando solicito o cancelamento do pedido e confirmo a operação
    ...                Então o sistema deve alterar a situação do pedido para "Cancelado"
    [Tags]    RF008    positivo    regression
    Skip    Pendente de automação
    # Obs.: O botão "Cancelar pedido" abre a janela confirm(): a keyword já confirma.
    # Abrir o pedido com status    Em preparo
    # Cancelar o pedido com confirmação
    # Verificar mensagem de sucesso do pedido    Pedido cancelado.
    # Verificar o status do pedido    Cancelado

RF008-N - cancelar pedido já entregue
    [Documentation]    Dado que existe um pedido com status "Entregue"
    ...                Quando solicito o cancelamento do pedido
    ...                Então o sistema deve impedir o cancelamento
    ...                E deve manter o status "Entregue"
    [Tags]    RF008    negativo    regression
    Skip    Pendente de automação
    # Obs.: Um banco novo não tem pedido "Entregue": o teste avança o pedido "Pronto"
    #       (cliente Ana, dos dados de exemplo) até "Entregue" antes de cancelar.
    #       "Avançar o status para" é do bloco da Pessoa 3 (RF006).
    #       O botão "Cancelar pedido" continua aparecendo para pedido Entregue (PED-79)
    #       e também abre a janela confirm().
    # Abrir o pedido com status    Pronto
    # Avançar o status para    Entregue
    # Verificar o status do pedido    Entregue
    # Cancelar o pedido com confirmação
    # Verificar mensagem de erro do pedido    Não é possível cancelar um pedido já entregue.
    # Verificar o status do pedido    Entregue
