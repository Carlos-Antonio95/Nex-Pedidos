*** Settings ***
Documentation    Ações da tela de login — Pessoa 5 (RF010).
Resource         Base.robot
Resource         ../Pages/Login_Page.robot
Resource         ../Pages/Pedidos_Page.robot


*** Keywords ***
# ===== Pessoa 5 — RF010 =====
Verificar que estou na tela de login
    [Documentation]    Confere que a tela de login está aberta.
    Fail    TODO: Wait Until Element Is Visible com \${LOGIN_TITULO}

Informar e-mail e senha no login
    [Documentation]    Preenche os campos de e-mail e senha da tela de login.
    [Arguments]    ${email}    ${senha}
    Fail    TODO: Input Text em \${LOGIN_CAMPO_EMAIL} e Input Password em \${LOGIN_CAMPO_SENHA}

Clicar no botão Entrar
    [Documentation]    Envia o formulário de login.
    Fail    TODO: Click Button em \${LOGIN_BOTAO_ENTRAR}

Verificar que a tela inicial foi exibida
    [Documentation]    Depois do login, o sistema abre a lista de pedidos (pedidos.php).
    Fail    TODO: Wait Until Location Contains pedidos.php e Wait Until Element Is Visible com \${PEDIDOS_TITULO}

Verificar mensagem de erro do login
    [Documentation]    Confere a mensagem de erro exibida no login
    ...                (ex.: "E-mail ou senha incorretos.").
    [Arguments]    ${mensagem}
    Fail    TODO: Wait Until Element Is Visible e Element Should Contain com \${LOGIN_MENSAGEM_ERRO}

Verificar que continuo na tela de login
    [Documentation]    Garante que o acesso foi impedido (não saiu da tela de login).
    Fail    TODO: Location Should Contain login.php e Element Should Be Visible com \${LOGIN_CAMPO_SENHA}
