*** Settings ***
Documentation    Ações das telas do cardápio — Pessoa 1 (RF001 e RF002).
Resource         Base.robot
Resource         ../Pages/Cardapio_Page.robot


*** Keywords ***
# ===== Pessoa 1 — RF001 =====
Acessar o cardápio
    [Documentation]    Abre a listagem do cardápio.
    Fail    TODO: Go To \${URL}/cardapio.php e Wait Until Element Is Visible com \${CARDAPIO_TITULO}

Acessar a tela de cadastro de itens do cardápio
    [Documentation]    Abre o formulário "Adicionar item".
    Fail    TODO: Acessar o cardápio, Click Link em \${CARDAPIO_BOTAO_ADICIONAR_ITEM} e esperar \${CARDAPIO_TITULO_CADASTRO}

Preencher o formulário do item
    [Documentation]    Preenche nome, descrição, preço e categoria do item.
    [Arguments]    ${nome}    ${descricao}    ${preco}    ${categoria}
    Fail    TODO: Input Text em \${CARDAPIO_CAMPO_NOME}, \${CARDAPIO_CAMPO_DESCRICAO}, \${CARDAPIO_CAMPO_PRECO} e \${CARDAPIO_CAMPO_CATEGORIA}

Clicar no botão Cadastrar item
    [Documentation]    Salva um item novo. No Gherkin o botão se chama "Salvar",
    ...                mas na tela o texto é "Cadastrar item".
    Fail    TODO: Click Button em \${CARDAPIO_BOTAO_CADASTRAR_ITEM}

Verificar mensagem de sucesso do cardápio
    [Documentation]    Confere a mensagem verde da listagem (ex.: "Item cadastrado com sucesso.").
    [Arguments]    ${mensagem}
    Fail    TODO: Wait Until Element Is Visible e Element Should Contain com \${CARDAPIO_MENSAGEM_SUCESSO}

Verificar mensagem de erro do cardápio
    [Documentation]    Confere a mensagem vermelha (ex.: "O preço deve ser maior que zero.").
    [Arguments]    ${mensagem}
    Fail    TODO: Wait Until Element Is Visible e Element Should Contain com \${CARDAPIO_MENSAGEM_ERRO}

Verificar que o item aparece na listagem
    [Documentation]    Confere que existe um card com a descrição informada.
    ...                Use a descrição porque os dados de exemplo já têm um "Suco de laranja".
    [Arguments]    ${descricao}
    Fail    TODO: Montar XPath com \${CARDAPIO_CARD_POR_DESCRICAO} e Page Should Contain Element

Verificar que continuo no formulário de cadastro
    [Documentation]    Garante que o cadastro foi impedido (a tela não voltou para a listagem).
    Fail    TODO: Element Should Be Visible com \${CARDAPIO_TITULO_CADASTRO}

# ===== Pessoa 1 — RF002 =====
Editar a descrição do item
    [Documentation]    Abre a edição do item que tem a descrição atual, troca a descrição e salva.
    [Arguments]    ${descricao_atual}    ${nova_descricao}
    Fail    TODO: Montar XPath com \${CARDAPIO_LINK_EDITAR_POR_DESCRICAO}, clicar, esperar \${CARDAPIO_TITULO_EDICAO}, Input Text em \${CARDAPIO_CAMPO_DESCRICAO} e Click Button em \${CARDAPIO_BOTAO_SALVAR_ALTERACOES}

Excluir o item com confirmação
    [Documentation]    Clica em "Excluir" no card com a descrição informada e confirma a janela.
    [Arguments]    ${descricao}
    Fail    TODO: Montar XPath com \${CARDAPIO_BOTAO_EXCLUIR_POR_DESCRICAO}, Click Button e Confirmar a janela do navegador

Verificar que o item não aparece na listagem
    [Documentation]    Confere que não existe mais card com a descrição informada.
    [Arguments]    ${descricao}
    Fail    TODO: Montar XPath com \${CARDAPIO_CARD_POR_DESCRICAO} e Page Should Not Contain Element

Contar os itens do cardápio
    [Documentation]    Retorna quantos cards existem na listagem.
    Fail    TODO: Get Element Count com \${CARDAPIO_CARDS} e RETURN do resultado

Verificar a quantidade de itens do cardápio
    [Documentation]    Confere que a listagem tem a quantidade de cards informada.
    [Arguments]    ${quantidade}
    Fail    TODO: Page Should Contain Element com \${CARDAPIO_CARDS} e limit=\${quantidade}

# A tela não tem botão para um item que não existe. Para o RF002-N, envie o
# mesmo POST que o botão "Excluir" envia, criando o formulário via JavaScript:
#   Execute Javascript
#   ...    var f = document.createElement('form'); f.method = 'post'; f.action = 'cardapio.php';
#   ...    f.innerHTML = '<input name="acao" value="excluir"><input name="id" value="${id}">';
#   ...    document.body.appendChild(f); f.submit();
Excluir o item pelo id
    [Documentation]    Envia o POST de exclusão para um id qualquer (RF002-N usa 9999).
    [Arguments]    ${id}
    Fail    TODO: Execute Javascript com o código do comentário acima e depois Wait Until Element Is Visible com \${CARDAPIO_TITULO}
