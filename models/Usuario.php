<?php
/**
 * Model dos usuários (Sprint 4 — RF009 e RF010).
 *
 * Assim como nos outros models, cadastrar() retorna um array de erros:
 * se vier vazio, deu certo. autenticar() também precisa devolver o
 * usuário encontrado, então retorna ['usuario' => ..., 'erros' => [...]].
 */

require_once __DIR__ . '/../config/database.php';

class Usuario
{
    // Tamanho mínimo da senha (RF009)
    const TAMANHO_MINIMO_SENHA = 6;

    private $pdo;

    public function __construct()
    {
        $this->pdo = conexao();
    }

    /**
     * RF009 — Cadastra um novo usuário com nome, e-mail e senha.
     */
    public function cadastrar($dados)
    {
        $erros = $this->validar($dados);
        if (!empty($erros)) {
            return $erros;
        }

        // A senha nunca é gravada em texto puro: guardamos apenas o hash
        $stmt = $this->pdo->prepare('INSERT INTO usuarios (nome, email, senha_hash) VALUES (?, ?, ?)');
        $stmt->execute([
            trim($dados['nome']),
            trim($dados['email']),
            password_hash($dados['senha'], PASSWORD_DEFAULT),
        ]);

        return [];
    }

    /**
     * RF010 — Confere e-mail e senha.
     *
     * Retorna ['usuario' => [id, nome, email], 'erros' => []] quando dá certo.
     * Em caso de erro, a mensagem é a mesma para e-mail ou senha errados,
     * para não revelar quais e-mails existem no sistema.
     */
    public function autenticar($email, $senha)
    {
        $email = trim((string) $email);
        $senha = (string) $senha;

        if ($email === '' || $senha === '') {
            return ['usuario' => null, 'erros' => ['Informe o e-mail e a senha.']];
        }

        $usuario = $this->buscarPorEmail($email);

        if ($usuario === null || !password_verify($senha, $usuario['senha_hash'])) {
            return ['usuario' => null, 'erros' => ['E-mail ou senha incorretos.']];
        }

        unset($usuario['senha_hash']); // não leva o hash para a sessão

        return ['usuario' => $usuario, 'erros' => []];
    }

    /**
     * Busca um usuário pelo e-mail (sem diferenciar maiúsculas). Retorna null se não existir.
     */
    public function buscarPorEmail($email)
    {
        $stmt = $this->pdo->prepare('SELECT id, nome, email, senha_hash FROM usuarios WHERE email = ? COLLATE NOCASE');
        $stmt->execute([trim((string) $email)]);
        $usuario = $stmt->fetch();

        return $usuario ? $usuario : null;
    }

    /**
     * Valida os dados do cadastro e retorna a lista de erros encontrados.
     */
    public function validar($dados)
    {
        $erros = [];
        $nome = trim($dados['nome'] ?? '');
        $email = trim($dados['email'] ?? '');
        $senha = (string) ($dados['senha'] ?? '');

        if ($nome === '') {
            $erros[] = 'O nome é obrigatório.';
        }

        if ($email === '') {
            $erros[] = 'O e-mail é obrigatório.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'Informe um e-mail válido.';
        } elseif ($this->buscarPorEmail($email) !== null) {
            $erros[] = 'Este e-mail já está cadastrado.';
        }

        // Conta caracteres (não bytes), para letras com acento valerem 1.
        // preg_match_all funciona mesmo sem a extensão mbstring instalada.
        $tamanho = preg_match_all('/./us', $senha);
        if ($tamanho < self::TAMANHO_MINIMO_SENHA) {
            $erros[] = 'A senha deve ter no mínimo 6 caracteres.';
        }

        return $erros;
    }
}
