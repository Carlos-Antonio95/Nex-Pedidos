*** Variables ***
# Tela de detalhe do pedido (public/pedido.php → views/pedidos/detalhe.php).
# Tem os itens, o total, o formulário de adicionar item, o botão de próximo
# status e o botão "Cancelar pedido".
# Somente XPaths. As ações ficam em Keywords/Pedido_Detalhe_Steps.robot.
# Variáveis com {} são MODELOS: use a keyword "Montar XPath" (Base.robot).
${DETALHE_TITULO}                     //h1[starts-with(normalize-space(),'Pedido #')]
${DETALHE_DESTINO}                    //div[contains(@class,'detail-meta')]/span[not(contains(@class,'badge'))]
${DETALHE_BADGE_STATUS}               //div[contains(@class,'detail-meta')]//span[contains(@class,'badge')]
${DETALHE_MENSAGEM_SUCESSO}           //div[contains(@class,'notice-success')]
${DETALHE_MENSAGEM_ERRO}              //div[contains(@class,'notice-error')]
${DETALHE_LINK_VOLTAR}                //a[normalize-space()='Voltar aos pedidos']

# ---------- Itens e total ----------
${DETALHE_SEM_ITENS}                  //p[normalize-space()='Nenhum item adicionado a este pedido.']
${DETALHE_LINHAS_ITENS}               //li[contains(@class,'order-item-row')]
${DETALHE_TOTAL}                      //div[contains(@class,'detail-total')]/strong

# Modelos por NOME do item (ex.: Montar XPath    ${DETALHE_LINHA_DO_ITEM}    Hambúrguer)
${DETALHE_LINHA_DO_ITEM}              //li[contains(@class,'order-item-row')][.//span[contains(@class,'order-item-name')][normalize-space(text())='{}']]
# Texto no formato "2 × R$ 20,00" (quantidade × preço unitário)
${DETALHE_QUANTIDADE_DO_ITEM}         ${DETALHE_LINHA_DO_ITEM}//span[contains(@class,'order-item-name')]/small
${DETALHE_SUBTOTAL_DO_ITEM}           ${DETALHE_LINHA_DO_ITEM}//span[contains(@class,'order-item-total')]
${DETALHE_BOTAO_REMOVER_1_DO_ITEM}    ${DETALHE_LINHA_DO_ITEM}//button[normalize-space()='Remover 1']

# ---------- Adicionar item ----------
${DETALHE_SELECT_ITEM}                //select[@id='item_id']
# As opções têm o texto "Hambúrguer — R$ 20,00"; o modelo recebe só o nome
${DETALHE_OPCAO_DO_ITEM}              //select[@id='item_id']/option[starts-with(normalize-space(),'{} —')]
${DETALHE_CAMPO_QUANTIDADE}           //form[contains(@class,'detail-form')]//input[@name='quantidade']
${DETALHE_BOTAO_ADICIONAR}            //button[normalize-space()='Adicionar ao pedido']

# ---------- Status e cancelamento ----------
# Modelo pelo status (ex.: Montar XPath    ${DETALHE_BOTAO_MARCAR_COMO}    Em preparo)
${DETALHE_BOTAO_MARCAR_COMO}          //button[normalize-space()='Marcar como {}']
# Todos os botões "Marcar como ..." da tela (a tela só mostra a próxima etapa)
${DETALHE_BOTOES_MARCAR_COMO}         //button[starts-with(normalize-space(),'Marcar como')]
${DETALHE_BOTAO_CANCELAR_PEDIDO}      //button[normalize-space()='Cancelar pedido']
