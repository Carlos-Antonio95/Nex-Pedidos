*** Variables ***
# Tela de cadastro de usuário (public/cadastro.php → views/usuarios/cadastro.php).
# Somente XPaths. As ações ficam em Keywords/Cadastro_Steps.robot.
# Depois de cadastrar, o sistema volta para o login: a mensagem de sucesso
# fica em ${LOGIN_MENSAGEM_SUCESSO} (Login_Page.robot).
${CADASTRO_TITULO}            //h1[normalize-space()='Criar conta']
${CADASTRO_CAMPO_NOME}        //input[@id='nome']
${CADASTRO_CAMPO_EMAIL}       //input[@id='email']
${CADASTRO_CAMPO_SENHA}       //input[@id='senha']
${CADASTRO_BOTAO_CADASTRAR}   //button[normalize-space()='Cadastrar']
${CADASTRO_LINK_ENTRAR}       //a[normalize-space()='Entrar']

${CADASTRO_MENSAGEM_ERRO}     //div[contains(@class,'notice-error')]
