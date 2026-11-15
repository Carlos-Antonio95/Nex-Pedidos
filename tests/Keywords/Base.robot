*** Settings ***
Documentation    Configuração comum a todos os testes (Pessoa 6).
...              Abre e fecha o navegador, faz login com o administrador
...              e oferece keywords de apoio usadas por todos os Steps.
Library          SeleniumLibrary
Library          String
Resource         ../Pages/Login_Page.robot
Resource         ../Pages/Pedidos_Page.robot


*** Variables ***
${URL}            http://localhost:8000
${BROWSER}        chrome
${TIMEOUT}        5s
${EMAIL_ADMIN}    admin@nexpedidos.com
${SENHA_ADMIN}    admin123


*** Keywords ***
Abrir o sistema
    [Documentation]    Abre o navegador na tela de login (usado no RF009 e no RF010).
    Open Browser    ${URL}/login.php    ${BROWSER}
    Maximize Browser Window
    Set Selenium Timeout    ${TIMEOUT}
    Wait Until Element Is Visible    ${LOGIN_CAMPO_EMAIL}

Abrir o sistema logado
    [Documentation]    Abre o sistema e entra com o administrador.
    ...                Usado do RF001 ao RF008, porque todas as telas exigem login.
    Abrir o sistema
    Input Text        ${LOGIN_CAMPO_EMAIL}    ${EMAIL_ADMIN}
    Input Password    ${LOGIN_CAMPO_SENHA}    ${SENHA_ADMIN}
    Click Button      ${LOGIN_BOTAO_ENTRAR}
    Wait Until Location Contains     pedidos.php
    Wait Until Element Is Visible    ${PEDIDOS_TITULO}

Fechar o sistema
    [Documentation]    Fecha o navegador (Test Teardown de todos os cenários).
    Close Browser

Confirmar a janela do navegador
    [Documentation]    Clica em "OK" na janela de confirmação do navegador (confirm()).
    ...                Os botões "Excluir" (cardápio) e "Cancelar pedido" abrem essa janela.
    Handle Alert    ACCEPT    ${TIMEOUT}

Montar XPath
    [Documentation]    Troca o {} de um XPath modelo das Pages pelo texto informado.
    ...                Exemplo: Montar XPath    \${CARDAPIO_CARD_DO_ITEM}    Hambúrguer
    [Arguments]    ${modelo}    ${texto}
    ${xpath}=    Replace String    ${modelo}    {}    ${texto}
    RETURN    ${xpath}
