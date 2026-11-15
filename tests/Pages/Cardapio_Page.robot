*** Variables ***
# Telas do cardápio (public/cardapio.php):
# - listagem: views/cardapio/lista.php
# - formulário de cadastro/edição: views/cardapio/form.php
# Somente XPaths. As ações ficam em Keywords/Cardapio_Steps.robot.
# Variáveis com {} são MODELOS: use a keyword "Montar XPath" (Base.robot)
# para trocar o {} pelo nome (ou descrição) do item.
# ---------- Listagem ----------
${CARDAPIO_TITULO}                    //h1[normalize-space()='Cardápio']
${CARDAPIO_BOTAO_ADICIONAR_ITEM}      //div[contains(@class,'page-heading')]//a[contains(normalize-space(),'Adicionar item')]
${CARDAPIO_CONTADOR_ITENS}            //div[contains(@class,'menu-toolbar')]//strong
${CARDAPIO_CARDS}                     //article[contains(@class,'menu-card')]
${CARDAPIO_LISTA_VAZIA}               //div[contains(@class,'empty-state')]
${CARDAPIO_MENSAGEM_SUCESSO}          //div[contains(@class,'notice-success')]
${CARDAPIO_MENSAGEM_ERRO}             //div[contains(@class,'notice-error')]

# Modelos por NOME do item (ex.: Montar XPath    ${CARDAPIO_CARD_DO_ITEM}    Hambúrguer)
${CARDAPIO_CARD_DO_ITEM}              //article[contains(@class,'menu-card')][.//h3[normalize-space()='{}']]
${CARDAPIO_PRECO_DO_ITEM}             ${CARDAPIO_CARD_DO_ITEM}//span[contains(@class,'menu-price')]
${CARDAPIO_CATEGORIA_DO_ITEM}         ${CARDAPIO_CARD_DO_ITEM}//span[contains(@class,'menu-category')]
${CARDAPIO_DESCRICAO_DO_ITEM}         ${CARDAPIO_CARD_DO_ITEM}//p[contains(@class,'menu-description')]
${CARDAPIO_LINK_EDITAR_DO_ITEM}       ${CARDAPIO_CARD_DO_ITEM}//a[normalize-space()='Editar']
${CARDAPIO_BOTAO_EXCLUIR_DO_ITEM}     ${CARDAPIO_CARD_DO_ITEM}//button[normalize-space()='Excluir']

# Modelos por DESCRIÇÃO do item: úteis quando há dois itens com o mesmo nome
# (os dados de exemplo já têm um "Suco de laranja")
${CARDAPIO_CARD_POR_DESCRICAO}            //article[contains(@class,'menu-card')][.//p[contains(@class,'menu-description')][normalize-space()='{}']]
${CARDAPIO_LINK_EDITAR_POR_DESCRICAO}     ${CARDAPIO_CARD_POR_DESCRICAO}//a[normalize-space()='Editar']
${CARDAPIO_BOTAO_EXCLUIR_POR_DESCRICAO}   ${CARDAPIO_CARD_POR_DESCRICAO}//button[normalize-space()='Excluir']

# ---------- Formulário (cadastro e edição) ----------
${CARDAPIO_TITULO_CADASTRO}           //h1[normalize-space()='Adicionar item']
${CARDAPIO_TITULO_EDICAO}             //h1[normalize-space()='Editar item']
${CARDAPIO_CAMPO_NOME}                //input[@id='nome']
${CARDAPIO_CAMPO_DESCRICAO}           //textarea[@id='descricao']
${CARDAPIO_CAMPO_PRECO}               //input[@id='preco']
${CARDAPIO_CAMPO_CATEGORIA}           //input[@id='categoria']
${CARDAPIO_BOTAO_CADASTRAR_ITEM}      //button[normalize-space()='Cadastrar item']
${CARDAPIO_BOTAO_SALVAR_ALTERACOES}   //button[normalize-space()='Salvar alterações']
${CARDAPIO_BOTAO_VOLTAR}              //a[normalize-space()='Voltar']
