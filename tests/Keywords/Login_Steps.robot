*** Settings ***
Documentation    Ações da tela de login — Pessoa 5 (RF010).
Resource         Base.robot
Resource         ../Pages/Login_Page.robot
Resource         ../Pages/Pedidos_Page.robot


*** Keywords ***
# ===== Pessoa 5 — RF010 =====
Verificar que estou na tela de login
    [Documentation]    Confere que a tela de login está aberta.
    Wait Until Element Is Visible    ${LOGIN_TITULO}

Informar e-mail e senha no login
    [Documentation]    Preenche os campos de e-mail e senha da tela de login.
    [Arguments]    ${email}    ${senha}
    Input Text        ${LOGIN_CAMPO_EMAIL}    ${email}
    Input Password    ${LOGIN_CAMPO_SENHA}    ${senha}

Clicar no botão Entrar
    [Documentation]    Envia o formulário de login.
    Click Button    ${LOGIN_BOTAO_ENTRAR}

Verificar que a tela inicial foi exibida
    [Documentation]    Depois do login, o sistema abre a lista de pedidos (pedidos.php).
    Wait Until Location Contains     pedidos.php
    Wait Until Element Is Visible    ${PEDIDOS_TITULO}

Verificar mensagem de erro do login
    [Documentation]    Confere a mensagem de erro exibida no login
    ...                (ex.: "E-mail ou senha incorretos.").
    [Arguments]    ${mensagem}
    Wait Until Element Is Visible    ${LOGIN_MENSAGEM_ERRO}
    Element Should Contain           ${LOGIN_MENSAGEM_ERRO}    ${mensagem}

Verificar que continuo na tela de login
    [Documentation]    Garante que o acesso foi impedido (não saiu da tela de login).
    Location Should Contain      login.php
    Element Should Be Visible    ${LOGIN_CAMPO_SENHA}
