*** Settings ***
Documentation    Ações da tela de detalhe do pedido (itens, total, status e cancelamento).
...              Arquivo compartilhado: cada pessoa mexe SÓ no seu bloco.
Resource         Base.robot
Resource         ../Pages/Pedido_Detalhe_Page.robot


*** Keywords ***
# ===== Comum — Pessoa 6 =====
Verificar mensagem de sucesso do pedido
    [Documentation]    Confere a mensagem verde (ex.: "Item adicionado ao pedido.").
    [Arguments]    ${mensagem}
    Wait Until Element Is Visible    ${DETALHE_MENSAGEM_SUCESSO}
    Element Should Contain           ${DETALHE_MENSAGEM_SUCESSO}    ${mensagem}

Verificar mensagem de erro do pedido
    [Documentation]    Confere a mensagem vermelha
    ...                (ex.: "A quantidade deve ser um número inteiro maior que zero.").
    [Arguments]    ${mensagem}
    Wait Until Element Is Visible    ${DETALHE_MENSAGEM_ERRO}
    Element Should Contain           ${DETALHE_MENSAGEM_ERRO}    ${mensagem}

Verificar o status do pedido
    [Documentation]    Confere o texto do badge de status (ex.: "Em preparo").
    [Arguments]    ${status}
    Element Text Should Be    ${DETALHE_BADGE_STATUS}    ${status}

Adicionar item ao pedido
    [Documentation]    Escolhe o item pelo nome, informa a quantidade e clica em
    ...                "Adicionar ao pedido". Usado no RF004 e no RF005.
    [Arguments]    ${item}    ${quantidade}
    ${opcao}=    Montar XPath    ${DETALHE_OPCAO_DO_ITEM}    ${item}
    ${valor}=    Get Element Attribute    ${opcao}    value
    Select From List By Value    ${DETALHE_SELECT_ITEM}    ${valor}
    Input Text    ${DETALHE_CAMPO_QUANTIDADE}    ${quantidade}
    Click Button    ${DETALHE_BOTAO_ADICIONAR}

# ===== Pessoa 2 — RF004 =====
Remover uma unidade do item
    [Documentation]    Clica em "Remover 1" na linha do item.
    [Arguments]    ${item}
    ${xpath}=    Montar XPath    ${DETALHE_BOTAO_REMOVER_1_DO_ITEM}    ${item}
    Click Button    ${xpath}

Verificar a quantidade do item no pedido
    [Documentation]    Confere a quantidade na linha do item (texto "1 × R$ 20,00").
    [Arguments]    ${item}    ${quantidade}
    ${xpath}=    Montar XPath    ${DETALHE_QUANTIDADE_DO_ITEM}    ${item}
    ${texto}=    Get Text    ${xpath}
    Should Start With    ${texto}    ${quantidade} ×

Verificar que o item não está no pedido
    [Documentation]    Confere que não há linha para o item.
    [Arguments]    ${item}
    ${xpath}=    Montar XPath    ${DETALHE_LINHA_DO_ITEM}    ${item}
    Page Should Not Contain Element    ${xpath}

# ===== Pessoa 3 — RF005 =====
Pegar o total do pedido
    [Documentation]    Retorna o texto do total (ex.: "R$ 28,50"), para comparar depois.
    Fail    TODO: Get Text em \${DETALHE_TOTAL} e RETURN do resultado

Verificar o total do pedido
    [Documentation]    Confere o total exibido (ex.: "R$ 48,00").
    [Arguments]    ${total}
    Fail    TODO: Element Text Should Be em \${DETALHE_TOTAL}

# ===== Pessoa 3 — RF006 =====
Avançar o status para
    [Documentation]    Clica em "Marcar como <status>". Também usado no RF008-N.
    [Arguments]    ${status}
    Fail    TODO: Montar XPath com \${DETALHE_BOTAO_MARCAR_COMO}, Click Button e Wait Until Element Is Visible com \${DETALHE_MENSAGEM_SUCESSO}

Verificar que a única etapa oferecida é
    [Documentation]    A tela só mostra o botão da próxima etapa: confere que existe
    ...                exatamente um botão "Marcar como ..." e que é o do status informado.
    [Arguments]    ${status}
    Fail    TODO: Page Should Contain Element com \${DETALHE_BOTOES_MARCAR_COMO} e limit=1; Montar XPath com \${DETALHE_BOTAO_MARCAR_COMO} e Page Should Contain Element

Verificar que a etapa não é oferecida
    [Documentation]    Confere que não existe botão "Marcar como <status>" (ex.: "Pronto").
    [Arguments]    ${status}
    Fail    TODO: Montar XPath com \${DETALHE_BOTAO_MARCAR_COMO} e Page Should Not Contain Element

# ===== Pessoa 4 — RF008 =====
Cancelar o pedido com confirmação
    [Documentation]    Clica em "Cancelar pedido" e confirma a janela do navegador.
    Fail    TODO: Click Button em \${DETALHE_BOTAO_CANCELAR_PEDIDO} e Confirmar a janela do navegador
