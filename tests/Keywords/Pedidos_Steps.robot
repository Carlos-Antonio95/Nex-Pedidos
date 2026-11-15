*** Settings ***
Documentation    Ações da tela de lista de pedidos (filtro e "Abrir pedido").
...              Arquivo compartilhado: cada pessoa mexe SÓ no seu bloco.
Resource         Base.robot
Resource         ../Pages/Pedidos_Page.robot
Resource         ../Pages/Pedido_Detalhe_Page.robot


*** Keywords ***
# ===== Comum — Pessoa 6 =====
Acessar a tela de pedidos
    [Documentation]    Abre a lista de pedidos (todos os status).
    Go To    ${URL}/pedidos.php
    Wait Until Element Is Visible    ${PEDIDOS_TITULO}

Abrir o pedido com status
    [Documentation]    PRONTA. Filtra a lista pelo status e abre o 1º pedido
    ...                (o mais recente). Falha se não houver pedido com esse status.
    [Arguments]    ${status}
    Go To    ${URL}/pedidos.php
    Wait Until Element Is Visible    ${PEDIDOS_FILTRO_STATUS}
    Select From List By Value        ${PEDIDOS_FILTRO_STATUS}    ${status}
    Click Button                     ${PEDIDOS_BOTAO_FILTRAR}
    Wait Until Element Is Visible    ${PEDIDOS_LINK_ABRIR_PRIMEIRO}
    ...    error=Nenhum pedido com status "${status}" na lista.
    Click Element                    ${PEDIDOS_LINK_ABRIR_PRIMEIRO}
    Wait Until Element Is Visible    ${DETALHE_BADGE_STATUS}
    Element Text Should Be           ${DETALHE_BADGE_STATUS}    ${status}

Abrir um novo pedido para a mesa
    [Documentation]    Abre um pedido novo para a mesa informada e espera a tela
    ...                de detalhe do pedido. Usado como "Dado" no RF004 e no RF005.
    [Arguments]    ${mesa}
    Acessar a tela de pedidos
    Input Text    ${PEDIDOS_CAMPO_MESA}    ${mesa}
    Click Button    ${PEDIDOS_BOTAO_ABRIR_PEDIDO}
    Wait Until Element Is Visible    ${DETALHE_TITULO}

# ===== Pessoa 2 — RF003 =====
Informar a mesa do pedido
    [Documentation]    Preenche o número da mesa no formulário "Abrir pedido".
    [Arguments]    ${mesa}
    Input Text    ${PEDIDOS_CAMPO_MESA}    ${mesa}

Deixar mesa e cliente em branco
    [Documentation]    Garante que mesa e cliente estão vazios.
    Clear Element Text    ${PEDIDOS_CAMPO_MESA}
    Clear Element Text    ${PEDIDOS_CAMPO_CLIENTE}

Clicar em Abrir pedido
    [Documentation]    Envia o formulário "Abrir pedido".
    Click Button    ${PEDIDOS_BOTAO_ABRIR_PEDIDO}

Verificar que o pedido foi aberto para
    [Documentation]    Depois de abrir, o sistema mostra o detalhe do pedido com
    ...                "Pedido aberto com sucesso." e o destino (ex.: "Mesa 05").
    [Arguments]    ${destino}
    Wait Until Element Is Visible    ${DETALHE_MENSAGEM_SUCESSO}
    Element Should Contain           ${DETALHE_DESTINO}    ${destino}

Verificar mensagem de erro da lista de pedidos
    [Documentation]    Confere a mensagem vermelha da tela de pedidos
    ...                (ex.: "Informe o número da mesa ou o nome do cliente.").
    [Arguments]    ${mensagem}
    Wait Until Element Is Visible    ${PEDIDOS_MENSAGEM_ERRO}
    Element Should Contain           ${PEDIDOS_MENSAGEM_ERRO}    ${mensagem}

# ===== Pessoa 4 — RF007 =====
Verificar que existem pedidos com status
    [Documentation]    Confere que a lista (sem filtro) tem ao menos um pedido com o status.
    [Arguments]    ${status}
    Fail    TODO: Acessar a tela de pedidos, Montar XPath com \${PEDIDOS_BADGE_COM_STATUS} e Page Should Contain Element

Verificar que não existem pedidos com status
    [Documentation]    Confere que a lista (sem filtro) não tem pedido com o status.
    [Arguments]    ${status}
    Fail    TODO: Acessar a tela de pedidos, Montar XPath com \${PEDIDOS_BADGE_COM_STATUS} e Page Should Not Contain Element

Filtrar pedidos por status
    [Documentation]    Seleciona o status no filtro e clica em "Filtrar".
    [Arguments]    ${status}
    Fail    TODO: Select From List By Value em \${PEDIDOS_FILTRO_STATUS} e Click Button em \${PEDIDOS_BOTAO_FILTRAR}

Verificar que só aparecem pedidos com status
    [Documentation]    Confere que há pedidos na lista e que nenhum tem outro status.
    [Arguments]    ${status}
    Fail    TODO: Page Should Contain Element com \${PEDIDOS_LINHAS}; Montar XPath com \${PEDIDOS_BADGE_DIFERENTE_DE} e Page Should Not Contain Element

Verificar que a lista de pedidos está vazia
    [Documentation]    Confere que a tabela não aparece e que a mensagem é exibida
    ...                (ex.: "Nenhum pedido encontrado.").
    [Arguments]    ${mensagem}
    Fail    TODO: Page Should Not Contain Element com \${PEDIDOS_TABELA} e Element Text Should Be em \${PEDIDOS_MENSAGEM_LISTA_VAZIA}
