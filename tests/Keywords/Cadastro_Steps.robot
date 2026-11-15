*** Settings ***
Documentation    Ações da tela de cadastro de usuário — Pessoa 5 (RF009).
Resource         Base.robot
Resource         ../Pages/Cadastro_Page.robot
Resource         ../Pages/Login_Page.robot


*** Keywords ***
# ===== Pessoa 5 — RF009 =====
Acessar a tela de cadastro de usuário
    [Documentation]    A partir da tela de login, abre a tela "Criar conta".
    Click Link    ${LOGIN_LINK_CADASTRE_SE}
    Wait Until Element Is Visible    ${CADASTRO_TITULO}

Preencher o cadastro de usuário
    [Documentation]    Preenche nome, e-mail e senha do novo usuário.
    [Arguments]    ${nome}    ${email}    ${senha}
    Input Text        ${CADASTRO_CAMPO_NOME}     ${nome}
    Input Text        ${CADASTRO_CAMPO_EMAIL}    ${email}
    Input Password    ${CADASTRO_CAMPO_SENHA}    ${senha}

Clicar no botão Cadastrar
    [Documentation]    Envia o formulário de cadastro.
    Click Button    ${CADASTRO_BOTAO_CADASTRAR}

Verificar que o cadastro foi realizado com sucesso
    [Documentation]    Depois de cadastrar, o sistema volta para o login com a mensagem
    ...                "Cadastro realizado com sucesso. Faça login para continuar.".
    Wait Until Element Is Visible    ${LOGIN_MENSAGEM_SUCESSO}
    Element Should Contain           ${LOGIN_MENSAGEM_SUCESSO}    Cadastro realizado com sucesso

Verificar mensagem de erro do cadastro
    [Documentation]    Confere um trecho da mensagem de erro do cadastro (ex.: "6 caracteres").
    [Arguments]    ${trecho}
    Wait Until Element Is Visible    ${CADASTRO_MENSAGEM_ERRO}
    Element Should Contain           ${CADASTRO_MENSAGEM_ERRO}    ${trecho}

Verificar que continuo na tela de cadastro
    [Documentation]    Garante que o cadastro foi impedido (não foi para o login).
    Element Should Be Visible    ${CADASTRO_TITULO}
