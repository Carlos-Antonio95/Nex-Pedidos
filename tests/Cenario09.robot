*** Settings ***
Documentation    RF009 — Cadastrar usuário (Pessoa 5).
...              Sem XPath aqui: os passos usam as keywords de Keywords/.
...              O RF009-P cria o usuário maria@email.com, usado pelo Cenario10 (rodar em ordem).
Resource         Keywords/Base.robot
Resource         Keywords/Cadastro_Steps.robot
Test Setup       Abrir o sistema
Test Teardown    Fechar o sistema


*** Test Cases ***
RF009-P - cadastrar usuário com dados válidos
    [Documentation]    Dado que estou na tela de cadastro de usuário
    ...                E informei nome "Maria Silva", e-mail "maria@email.com" e senha "123456"
    ...                Quando clico no botão "Cadastrar"
    ...                Então o sistema deve cadastrar o usuário
    ...                E deve informar que o cadastro foi realizado com sucesso
    [Tags]    RF009    positivo    smoke    sanity    regression
    # Obs.: Depois de cadastrar, o sistema volta para o login com a mensagem de sucesso.
    Acessar a tela de cadastro de usuário
    Preencher o cadastro de usuário    Maria Silva    maria@email.com    123456
    Clicar no botão Cadastrar
    Verificar que o cadastro foi realizado com sucesso

RF009-N - cadastrar usuário com senha inválida
    [Documentation]    Dado que estou na tela de cadastro de usuário
    ...                E informei nome "Maria Silva", e-mail "maria@email.com" e senha "12345"
    ...                Quando clico no botão "Cadastrar"
    ...                Então o sistema deve impedir o cadastro
    ...                E deve informar que a senha deve ter 6 caracteres
    [Tags]    RF009    negativo    sanity    regression
    # Obs.: A mensagem real é "A senha deve ter no mínimo 6 caracteres.": confira o trecho "6 caracteres".
    #       Depois do RF009-P também aparece "Este e-mail já está cadastrado." (é esperado).
    Acessar a tela de cadastro de usuário
    Preencher o cadastro de usuário    Maria Silva    maria@email.com    12345
    Clicar no botão Cadastrar
    Verificar mensagem de erro do cadastro    6 caracteres
    Verificar que continuo na tela de cadastro
