<?php
/**
 * Controle de sessão do usuário (RF010).
 *
 * Inclua este arquivo nos controllers. Para proteger uma página,
 * chame exigirLogin() logo no início.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Retorna o usuário logado (id, nome, email) ou null.
 */
function usuarioLogado()
{
    return $_SESSION['usuario'] ?? null;
}

/**
 * Se não houver usuário logado, manda para a tela de login.
 */
function exigirLogin()
{
    if (usuarioLogado() === null) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Guarda o usuário na sessão depois de um login bem-sucedido.
 */
function entrar($usuario)
{
    // Gera um novo ID de sessão no login (evita sequestro de sessão)
    session_regenerate_id(true);
    $_SESSION['usuario'] = [
        'id'    => $usuario['id'],
        'nome'  => $usuario['nome'],
        'email' => $usuario['email'],
    ];
}

/**
 * Encerra a sessão do usuário.
 */
function sair()
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }

    session_destroy();
}
