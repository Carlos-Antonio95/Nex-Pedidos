*** Settings ***
Documentation    RF002 — Editar e excluir item do cardápio (Pessoa 1).
...              Sem XPath aqui: os passos usam as keywords de Keywords/.
Resource         Keywords/Base.robot
Resource         Keywords/Cardapio_Steps.robot
Test Setup       Abrir o sistema logado
Test Teardown    Fechar o sistema


*** Test Cases ***
RF002-P - editar e excluir item cadastrado
    [Documentation]    Dado que existe o item "Suco de laranja" cadastrado no cardápio
    ...                Quando altero sua descrição para "Suco natural 500 ml" e salvo
    ...                E em seguida solicito a exclusão do mesmo item e confirmo a operação
    ...                Então o sistema deve atualizar os dados do item
    ...                E deve removê-lo da listagem do cardápio
    [Tags]    RF002    positivo    regression
    # Obs.: O teste cadastra o próprio "Suco de laranja" antes, para não depender do Cenario01.
    #       O item é localizado pela descrição, porque pode haver mais de um "Suco de laranja".
    #       O botão "Excluir" abre a janela confirm(): a keyword de exclusão já confirma.
    Acessar a tela de cadastro de itens do cardápio
    Preencher o formulário do item    Suco de laranja    Suco natural 300 ml    8,50    Bebidas
    Clicar no botão Cadastrar item
    Editar a descrição do item    Suco natural 300 ml    Suco natural 500 ml
    Verificar mensagem de sucesso do cardápio    Item editado com sucesso.
    Verificar que o item aparece na listagem    Suco natural 500 ml
    Excluir o item com confirmação    Suco natural 500 ml
    Verificar mensagem de sucesso do cardápio    Item excluído com sucesso.
    Verificar que o item não aparece na listagem    Suco natural 500 ml

RF002-N - excluir item inexistente
    [Documentation]    Dado que não existe um item com o identificador "9999" no cardápio
    ...                Quando solicito a exclusão desse item
    ...                Então o sistema deve informar que o item não foi encontrado
    ...                E não deve alterar os demais itens do cardápio
    [Tags]    RF002    negativo    regression
    # Obs.: A tela não tem botão "Excluir" para um item que não existe: a keyword
    #       "Excluir o item pelo id" envia o mesmo POST do botão via Execute Javascript.
    Acessar o cardápio
    ${quantidade}=    Contar os itens do cardápio
    Excluir o item pelo id    9999
    Verificar mensagem de erro do cardápio    Item não encontrado.
    Verificar a quantidade de itens do cardápio    ${quantidade}
