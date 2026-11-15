*** Variables ***
# Tela da lista de pedidos (public/pedidos.php → views/pedidos/lista.php).
# Tem a tabela de pedidos, o filtro por status e o formulário "Abrir pedido".
# Somente XPaths. As ações ficam em Keywords/Pedidos_Steps.robot.
# Variáveis com {} são MODELOS: use a keyword "Montar XPath" (Base.robot).
${PEDIDOS_TITULO}                     //h1[normalize-space()='Pedidos']
${PEDIDOS_MENSAGEM_ERRO}              //div[contains(@class,'notice-error')]

# ---------- Filtro por status ----------
${PEDIDOS_FILTRO_STATUS}              //select[@id='status']
${PEDIDOS_BOTAO_FILTRAR}              //button[normalize-space()='Filtrar']

# ---------- Tabela de pedidos ----------
${PEDIDOS_TABELA}                     //table[contains(@class,'orders-table')]
${PEDIDOS_LINHAS}                     ${PEDIDOS_TABELA}//tbody/tr
${PEDIDOS_BADGES_STATUS}              ${PEDIDOS_TABELA}//span[contains(@class,'badge')]
${PEDIDOS_LINK_ABRIR_PRIMEIRO}        (${PEDIDOS_TABELA}//a[normalize-space()='Abrir pedido'])[1]

# Lista vazia ("Nenhum pedido encontrado.")
${PEDIDOS_LISTA_VAZIA}                //div[contains(@class,'empty-state')]
${PEDIDOS_MENSAGEM_LISTA_VAZIA}       ${PEDIDOS_LISTA_VAZIA}//h3

# Modelos por status (ex.: Montar XPath    ${PEDIDOS_BADGE_COM_STATUS}    Em preparo)
${PEDIDOS_BADGE_COM_STATUS}           ${PEDIDOS_TABELA}//span[contains(@class,'badge')][normalize-space()='{}']
${PEDIDOS_BADGE_DIFERENTE_DE}         ${PEDIDOS_TABELA}//span[contains(@class,'badge')][normalize-space()!='{}']

# Modelos pela coluna "CLIENTE / MESA" (ex.: Mesa 05 ou Ana).
# A lista vem do mais recente para o mais antigo, então [1] é o pedido mais novo.
${PEDIDOS_LINHA_DO_DESTINO}           ${PEDIDOS_TABELA}//tbody/tr[td[contains(@class,'customer-cell')][normalize-space()='{}']]
${PEDIDOS_LINK_ABRIR_DO_DESTINO}      (${PEDIDOS_LINHA_DO_DESTINO}//a[normalize-space()='Abrir pedido'])[1]

# ---------- Formulário "Abrir pedido" ----------
${PEDIDOS_CAMPO_MESA}                 //input[@id='mesa']
${PEDIDOS_CAMPO_CLIENTE}              //input[@id='cliente']
${PEDIDOS_BOTAO_ABRIR_PEDIDO}         //button[normalize-space()='Abrir pedido']
