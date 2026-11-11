<?php
// Cadastro de novos usuários
require __DIR__ . '/../funcoes.php';

// Quem já está logado não precisa se cadastrar
if (esta_logado()) {
    header('Location: inicio.php');
    exit;
}

$erros = [];
$nome = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar_senha'] ?? '';

    // Validações (as mesmas feitas no JavaScript)
    if ($nome === '') {
        $erros['nome'] = 'Informe o nome.';
    } elseif (strlen($nome) > 100) {
        $erros['nome'] = 'O nome deve ter no máximo 100 caracteres.';
    }

    if ($email === '') {
        $erros['email'] = 'Informe o e-mail.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = 'Informe um e-mail válido.';
    }

    if ($senha === '') {
        $erros['senha'] = 'Informe a senha.';
    } elseif (strlen($senha) < 6) {
        $erros['senha'] = 'A senha deve ter pelo menos 6 caracteres.';
    }

    if ($confirmar === '') {
        $erros['confirmar_senha'] = 'Confirme a senha.';
    } elseif ($senha !== $confirmar) {
        $erros['confirmar_senha'] = 'As senhas não são iguais.';
    }

    // Só no PHP: impede e-mail duplicado
    $usuarios = ler_json(ARQ_USUARIOS);
    if (!isset($erros['email'])) {
        foreach ($usuarios as $usuario) {
            if ($usuario['email'] === $email) {
                $erros['email'] = 'Este e-mail já está cadastrado.';
            }
        }
    }

    // Sem erros: salva o usuário com a senha criptografada
    if (count($erros) === 0) {
        $usuarios[] = [
            'id' => proximo_id($usuarios),
            'nome' => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
        ];
        salvar_json(ARQ_USUARIOS, $usuarios);
        definir_mensagem('sucesso', 'Cadastro realizado! Faça login para entrar.');
        header('Location: login.php');
        exit;
    }
}

cabecalho('Cadastro');
?>
<div class="card formulario">
    <h1>Criar conta</h1>
<?php if (count($erros) > 0) { ?>
    <div class="mensagem erro" role="alert">Corrija os campos indicados abaixo.</div>
<?php } ?>
    <form id="form-cadastro" method="post" action="cadastro.php" onsubmit="return validarCadastro()" novalidate>
        <div class="campo">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" maxlength="100" value="<?= htmlspecialchars($nome) ?>">
            <span class="erro-campo" id="erro-nome"><?= texto_erro($erros, 'nome') ?></span>
        </div>
        <div class="campo">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
            <span class="erro-campo" id="erro-email"><?= texto_erro($erros, 'email') ?></span>
        </div>
        <div class="campo">
            <label for="senha">Senha</label>
            <div class="campo-senha">
                <input type="password" id="senha" name="senha">
                <button type="button" class="botao-senha" onclick="alternarSenha('senha', this)" aria-label="Mostrar senha">Mostrar</button>
            </div>
            <span class="erro-campo" id="erro-senha"><?= texto_erro($erros, 'senha') ?></span>
        </div>
        <div class="campo">
            <label for="confirmar_senha">Confirmar senha</label>
            <div class="campo-senha">
                <input type="password" id="confirmar_senha" name="confirmar_senha">
                <button type="button" class="botao-senha" onclick="alternarSenha('confirmar_senha', this)" aria-label="Mostrar confirmação de senha">Mostrar</button>
            </div>
            <span class="erro-campo" id="erro-confirmar_senha"><?= texto_erro($erros, 'confirmar_senha') ?></span>
        </div>
        <button type="submit" class="botao">Cadastrar</button>
    </form>
    <p>Já tem conta? <a href="login.php">Entrar</a></p>
</div>
<?php
rodape();
