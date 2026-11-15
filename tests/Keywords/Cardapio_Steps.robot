*** Settings ***
Documentation    Ações das telas do cardápio — Pessoa 1 (RF001 e RF002).
Resource         Base.robot
Resource         ../Pages/Cardapio_Page.robot


*** Keywords ***
# ===== Pessoa 1 — RF001 =====
Acessar o cardápio
    [Documentation]    Abre a listagem do cardápio.
    Go To    ${URL}/cardapio.php
    Wait Until Element Is Visible    ${CARDAPIO_TITULO}

Acessar a tela de cadastro de itens do cardápio
    [Documentation]    Abre o formulário "Adicionar item".
    Acessar o cardápio
    Click Link    ${CARDAPIO_BOTAO_ADICIONAR_ITEM}
    Wait Until Element Is Visible    ${CARDAPIO_TITULO_CADASTRO}

Preencher o formulário do item
    [Documentation]    Preenche nome, descrição, preço e categoria do item.
    [Arguments]    ${nome}    ${descricao}    ${preco}    ${categoria}
    Input Text    ${CARDAPIO_CAMPO_NOME}         ${nome}
    Input Text    ${CARDAPIO_CAMPO_DESCRICAO}    ${descricao}
    Input Text    ${CARDAPIO_CAMPO_PRECO}        ${preco}
    Input Text    ${CARDAPIO_CAMPO_CATEGORIA}    ${categoria}

Clicar no botão Cadastrar item
    [Documentation]    Salva um item novo. No Gherkin o botão se chama "Salvar",
    ...                mas na tela o texto é "Cadastrar item".
    Click Button    ${CARDAPIO_BOTAO_CADASTRAR_ITEM}

Verificar mensagem de sucesso do cardápio
    [Documentation]    Confere a mensagem verde da listagem (ex.: "Item cadastrado com sucesso.").
    [Arguments]    ${mensagem}
    Wait Until Element Is Visible    ${CARDAPIO_MENSAGEM_SUCESSO}
    Element Should Contain           ${CARDAPIO_MENSAGEM_SUCESSO}    ${mensagem}

Verificar mensagem de erro do cardápio
    [Documentation]    Confere a mensagem vermelha (ex.: "O preço deve ser maior que zero.").
    [Arguments]    ${mensagem}
    Wait Until Element Is Visible    ${CARDAPIO_MENSAGEM_ERRO}
    Element Should Contain           ${CARDAPIO_MENSAGEM_ERRO}    ${mensagem}

Verificar que o item aparece na listagem
    [Documentation]    Confere que existe um card com a descrição informada.
    ...                Use a descrição porque os dados de exemplo já têm um "Suco de laranja".
    [Arguments]    ${descricao}
    ${xpath}=    Montar XPath    ${CARDAPIO_CARD_POR_DESCRICAO}    ${descricao}
    Page Should Contain Element    ${xpath}

Verificar que continuo no formulário de cadastro
    [Documentation]    Garante que o cadastro foi impedido (a tela não voltou para a listagem).
    Element Should Be Visible    ${CARDAPIO_TITULO_CADASTRO}

# ===== Pessoa 1 — RF002 =====
Editar a descrição do item
    [Documentation]    Abre a edição do item que tem a descrição atual, troca a descrição e salva.
    [Arguments]    ${descricao_atual}    ${nova_descricao}
    ${xpath}=    Montar XPath    ${CARDAPIO_LINK_EDITAR_POR_DESCRICAO}    ${descricao_atual}
    Click Link    ${xpath}
    Wait Until Element Is Visible    ${CARDAPIO_TITULO_EDICAO}
    Input Text    ${CARDAPIO_CAMPO_DESCRICAO}    ${nova_descricao}
    Click Button    ${CARDAPIO_BOTAO_SALVAR_ALTERACOES}

Excluir o item com confirmação
    [Documentation]    Clica em "Excluir" no card com a descrição informada e confirma a janela.
    [Arguments]    ${descricao}
    ${xpath}=    Montar XPath    ${CARDAPIO_BOTAO_EXCLUIR_POR_DESCRICAO}    ${descricao}
    Click Button    ${xpath}
    Confirmar a janela do navegador

Verificar que o item não aparece na listagem
    [Documentation]    Confere que não existe mais card com a descrição informada.
    [Arguments]    ${descricao}
    ${xpath}=    Montar XPath    ${CARDAPIO_CARD_POR_DESCRICAO}    ${descricao}
    Page Should Not Contain Element    ${xpath}

Contar os itens do cardápio
    [Documentation]    Retorna quantos cards existem na listagem.
    ${quantidade}=    Get Element Count    ${CARDAPIO_CARDS}
    RETURN    ${quantidade}

Verificar a quantidade de itens do cardápio
    [Documentation]    Confere que a listagem tem a quantidade de cards informada.
    [Arguments]    ${quantidade}
    Page Should Contain Element    ${CARDAPIO_CARDS}    limit=${quantidade}

# RF002-N: a tela não tem botão para um item que não existe, então a keyword
# abaixo envia o mesmo POST que o botão "Excluir" envia, via JavaScript.
Excluir o item pelo id
    [Documentation]    Envia o POST de exclusão para um id qualquer (RF002-N usa 9999).
    [Arguments]    ${id}
    Execute Javascript
    ...    var f = document.createElement('form'); f.method = 'post'; f.action = 'cardapio.php';
    ...    f.innerHTML = '<input name="acao" value="excluir"><input name="id" value="${id}">';
    ...    document.body.appendChild(f); f.submit();
    Wait Until Element Is Visible    ${CARDAPIO_TITULO}
