*** Settings ***
Documentation    RF010 — Realizar login (Pessoa 5).
...              Sem XPath aqui: os passos usam as keywords de Keywords/.
...              Depende do usuário maria@email.com criado no Cenario09 (rodar em ordem).
Resource         Keywords/Base.robot
Resource         Keywords/Login_Steps.robot
Test Setup       Abrir o sistema
Test Teardown    Fechar o sistema


*** Test Cases ***
RF010-P - realizar login com credenciais válidas
    [Documentation]    Dado que existe um usuário cadastrado com e-mail "maria@email.com" e senha "123456"
    ...                E estou na tela de login
    ...                Quando informo o e-mail "maria@email.com" e a senha "123456"
    ...                E clico no botão "Entrar"
    ...                Então o sistema deve autenticar o usuário
    ...                E deve exibir a tela inicial do sistema
    [Tags]    RF010    positivo    smoke    sanity    regression
    Skip    Pendente de automação
    # Obs.: A tela inicial é a lista de pedidos (pedidos.php).
    # Verificar que estou na tela de login
    # Informar e-mail e senha no login    maria@email.com    123456
    # Clicar no botão Entrar
    # Verificar que a tela inicial foi exibida

RF010-N - realizar login com senha incorreta
    [Documentation]    Dado que existe um usuário cadastrado com e-mail "maria@email.com" e senha "123456"
    ...                E estou na tela de login
    ...                Quando informo o e-mail "maria@email.com" e a senha "654321"
    ...                E clico no botão "Entrar"
    ...                Então o sistema deve impedir o acesso
    ...                E deve informar que e-mail ou senha estão incorretos
    [Tags]    RF010    negativo    sanity    regression
    Skip    Pendente de automação
    # Verificar que estou na tela de login
    # Informar e-mail e senha no login    maria@email.com    654321
    # Clicar no botão Entrar
    # Verificar mensagem de erro do login    E-mail ou senha incorretos.
    # Verificar que continuo na tela de login
