*** Settings ***
Documentation    RF004 — Adicionar e remover itens do pedido (Pessoa 2).
...              Sem XPath aqui: os passos usam as keywords de Keywords/.
...              "Abrir um novo pedido para a mesa" e "Adicionar item ao pedido" ficam no bloco Comum (Pessoa 6).
Resource         Keywords/Base.robot
Resource         Keywords/Pedidos_Steps.robot
Resource         Keywords/Pedido_Detalhe_Steps.robot
Test Setup       Abrir o sistema logado
Test Teardown    Fechar o sistema


*** Test Cases ***
RF004-P - adicionar e remover item com quantidade válida
    [Documentation]    Dado que existe um pedido aberto vinculado à mesa "05"
    ...                E o item "Hambúrguer" está disponível no cardápio
    ...                Quando adiciono 2 unidades do item ao pedido
    ...                E removo 1 unidade do mesmo item
    ...                Então o sistema deve manter 1 unidade do item no pedido
    [Tags]    RF004    positivo    smoke    regression
    Skip    Pendente de automação
    # Obs.: O teste abre o próprio pedido da mesa 05, para não depender do Cenario03.
    # Abrir um novo pedido para a mesa    05
    # Adicionar item ao pedido    Hambúrguer    2
    # Verificar mensagem de sucesso do pedido    Item adicionado ao pedido.
    # Remover uma unidade do item    Hambúrguer
    # Verificar mensagem de sucesso do pedido    Item removido do pedido.
    # Verificar a quantidade do item no pedido    Hambúrguer    1

RF004-N - adicionar item com quantidade inválida
    [Documentation]    Dado que existe um pedido aberto vinculado à mesa "05"
    ...                Quando tento adicionar 0 unidades do item "Hambúrguer"
    ...                Então o sistema deve impedir a inclusão
    ...                E deve informar que a quantidade deve ser um número inteiro maior que zero
    [Tags]    RF004    negativo    regression
    Skip    Pendente de automação
    # Abrir um novo pedido para a mesa    05
    # Adicionar item ao pedido    Hambúrguer    0
    # Verificar mensagem de erro do pedido    A quantidade deve ser um número inteiro maior que zero.
    # Verificar que o item não está no pedido    Hambúrguer
