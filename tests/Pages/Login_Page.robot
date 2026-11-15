*** Variables ***
# Tela de login (public/login.php → views/usuarios/login.php).
# Somente XPaths. As ações ficam em Keywords/Login_Steps.robot.
${LOGIN_TITULO}               //h1[normalize-space()='Entrar']
${LOGIN_CAMPO_EMAIL}          //input[@id='email']
${LOGIN_CAMPO_SENHA}          //input[@id='senha']
${LOGIN_BOTAO_ENTRAR}         //button[normalize-space()='Entrar']
${LOGIN_LINK_CADASTRE_SE}     //a[normalize-space()='Cadastre-se']

# Mensagens (o sucesso do cadastro de usuário, RF009, também aparece aqui)
${LOGIN_MENSAGEM_SUCESSO}     //div[contains(@class,'notice-success')]
${LOGIN_MENSAGEM_ERRO}        //div[contains(@class,'notice-error')]
