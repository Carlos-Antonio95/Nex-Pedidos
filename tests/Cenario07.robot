*** Settings ***
Documentation    RF007 — Listar e filtrar pedidos (Pessoa 4).
...              Sem XPath aqui: os passos usam as keywords de Keywords/.
Resource         Keywords/Base.robot
Resource         Keywords/Pedidos_Steps.robot
Test Setup       Abrir o sistema logado
Test Teardown    Fechar o sistema


*** Test Cases ***
RF007-P - filtrar pedidos por status
    [Documentation]    Dado que existem pedidos com status "Recebido" e "Em preparo"
    ...                Quando seleciono o filtro de status "Em preparo"
    ...                Então o sistema deve exibir somente os pedidos com status "Em preparo"
    [Tags]    RF007    positivo    regression
    # Obs.: Os dados de exemplo já têm pedidos "Recebido" e "Em preparo".
    Verificar que existem pedidos com status    Recebido
    Verificar que existem pedidos com status    Em preparo
    Filtrar pedidos por status    Em preparo
    Verificar que só aparecem pedidos com status    Em preparo

RF007-N - filtrar por status sem resultados
    [Documentation]    Dado que não existem pedidos com status "Entregue"
    ...                Quando seleciono o filtro de status "Entregue"
    ...                Então o sistema deve exibir a lista vazia
    ...                E deve informar que nenhum pedido foi encontrado
    [Tags]    RF007    negativo    regression
    # Obs.: Só passa num banco de teste novo e ANTES do Cenario08: o RF008-N cria um
    #       pedido "Entregue". Rodando a pasta tests/ inteira, a ordem já é essa.
    Verificar que não existem pedidos com status    Entregue
    Filtrar pedidos por status    Entregue
    Verificar que a lista de pedidos está vazia    Nenhum pedido encontrado.
