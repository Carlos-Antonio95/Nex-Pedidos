*** Settings ***
Documentation    Ações da tela de cadastro de usuário — Pessoa 5 (RF009).
Resource         Base.robot
Resource         ../Pages/Cadastro_Page.robot
Resource         ../Pages/Login_Page.robot


*** Keywords ***
# ===== Pessoa 5 — RF009 =====
Acessar a tela de cadastro de usuário
    [Documentation]    A partir da tela de login, abre a tela "Criar conta".
    Fail    TODO: Click Link em \${LOGIN_LINK_CADASTRE_SE} e Wait Until Element Is Visible com \${CADASTRO_TITULO}

Preencher o cadastro de usuário
    [Documentation]    Preenche nome, e-mail e senha do novo usuário.
    [Arguments]    ${nome}    ${email}    ${senha}
    Fail    TODO: Input Text em \${CADASTRO_CAMPO_NOME} e \${CADASTRO_CAMPO_EMAIL}; Input Password em \${CADASTRO_CAMPO_SENHA}

Clicar no botão Cadastrar
    [Documentation]    Envia o formulário de cadastro.
    Fail    TODO: Click Button em \${CADASTRO_BOTAO_CADASTRAR}

Verificar que o cadastro foi realizado com sucesso
    [Documentation]    Depois de cadastrar, o sistema volta para o login com a mensagem
    ...                "Cadastro realizado com sucesso. Faça login para continuar.".
    Fail    TODO: Wait Until Element Is Visible e Element Should Contain com \${LOGIN_MENSAGEM_SUCESSO}

Verificar mensagem de erro do cadastro
    [Documentation]    Confere um trecho da mensagem de erro do cadastro (ex.: "6 caracteres").
    [Arguments]    ${trecho}
    Fail    TODO: Wait Until Element Is Visible e Element Should Contain com \${CADASTRO_MENSAGEM_ERRO}

Verificar que continuo na tela de cadastro
    [Documentation]    Garante que o cadastro foi impedido (não foi para o login).
    Fail    TODO: Element Should Be Visible com \${CADASTRO_TITULO}
