<?php
/**
 * Model dos pedidos (Sprints 2 e 3).
 */

require_once __DIR__ . '/../config/database.php';

class Pedido
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = conexao();
    }

    public function abrir($mesa, $cliente)
    {
        // TODO Sprint 2 — RF003: abrir pedido informando mesa ou cliente
    }

    public function adicionarItem($pedidoId, $itemId, $quantidade)
    {
        // TODO Sprint 2 — RF004/RN002: adicionar item ao pedido
    }

    public function removerItem($pedidoId, $itemId, $quantidade)
    {
        // TODO Sprint 2 — RF004/RN002: remover item do pedido
    }

    public function calcularTotal($pedidoId)
    {
        // TODO Sprint 2 — RF005: calcular o total do pedido
    }

    public function alterarStatus($pedidoId, $novoStatus)
    {
        // TODO Sprint 3 — RF006/RN003: alterar o status do pedido
    }

    public function listar($status = null)
    {
        // TODO Sprint 3 — RF007: listar pedidos, com filtro opcional por status
    }

    public function cancelar($pedidoId)
    {
        // TODO Sprint 3 — RF008/RN004: cancelar o pedido
    }
}
