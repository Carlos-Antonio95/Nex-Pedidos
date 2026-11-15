*** Settings ***
Documentation    RF001 — Cadastrar item do cardápio (Pessoa 1).
...              Sem XPath aqui: os passos usam as keywords de Keywords/.
Resource         Keywords/Base.robot
Resource         Keywords/Cardapio_Steps.robot
Test Setup       Abrir o sistema logado
Test Teardown    Fechar o sistema


*** Test Cases ***
RF001-P - cadastrar item com dados válidos
    [Documentation]    Dado que estou na tela de cadastro de itens do cardápio
    ...                E informei nome "Suco de laranja", descrição "Suco natural 300 ml", preço "8,50" e categoria "Bebidas"
    ...                Quando clico no botão "Salvar"
    ...                Então o sistema deve cadastrar o item
    ...                E deve exibir o item na listagem do cardápio
    [Tags]    RF001    positivo    smoke    regression
    # Obs.: O Gherkin diz botão "Salvar", mas na tela o texto é "Cadastrar item"
    #       ("Salvar alterações" na edição). Use o texto real da tela.
    #       Os dados de exemplo já têm um "Suco de laranja": confira pela descrição.
    Acessar a tela de cadastro de itens do cardápio
    Preencher o formulário do item    Suco de laranja    Suco natural 300 ml    8,50    Bebidas
    Clicar no botão Cadastrar item
    Verificar mensagem de sucesso do cardápio    Item cadastrado com sucesso.
    Verificar que o item aparece na listagem    Suco natural 300 ml

RF001-N - cadastrar item com preço inválido
    [Documentation]    Dado que estou na tela de cadastro de itens do cardápio
    ...                E informei nome "Suco de laranja", descrição "Suco natural 300 ml", preço "0,00" e categoria "Bebidas"
    ...                Quando clico no botão "Salvar"
    ...                Então o sistema deve impedir o cadastro
    ...                E deve informar que o preço deve ser maior que zero
    [Tags]    RF001    negativo    regression
    # Obs.: O Gherkin diz botão "Salvar", mas na tela o texto é "Cadastrar item".
    Acessar a tela de cadastro de itens do cardápio
    Preencher o formulário do item    Suco de laranja    Suco natural 300 ml    0,00    Bebidas
    Clicar no botão Cadastrar item
    Verificar mensagem de erro do cardápio    O preço deve ser maior que zero.
    Verificar que continuo no formulário de cadastro
