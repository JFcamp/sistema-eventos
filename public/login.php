<?php
// Login do usuário
require 'funcoes.php';

// Quem já está logado vai direto para o início
if (esta_logado()) {
    header('Location: inicio.php');
    exit;
}

$erros = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $senha = $_POST['senha'] ?? '';

    // Validações (as mesmas feitas no JavaScript)
    if ($email === '') {
        $erros['email'] = 'Informe o e-mail.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = 'Informe um e-mail válido.';
    }
    if ($senha === '') {
        $erros['senha'] = 'Informe a senha.';
    }

    // Procura o usuário e confere a senha com password_verify
    if (count($erros) === 0) {
        $usuarios = ler_json(ARQ_USUARIOS);
        $encontrado = false;
        foreach ($usuarios as $usuario) {
            if ($usuario['email'] === $email && password_verify($senha, $usuario['senha'])) {
                $encontrado = true;
                // Guarda o usuário na sessão
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                definir_mensagem('sucesso', 'Login realizado com sucesso!');
                header('Location: inicio.php');
                exit;
            }
        }
        if (!$encontrado) {
            $erros['geral'] = 'E-mail ou senha incorretos.';
        }
    }
}

cabecalho('Login');
?>
<div class="card formulario">
    <h1>Entrar</h1>
<?php if (isset($erros['geral'])) { ?>
    <div class="mensagem erro" role="alert"><?= texto_erro($erros, 'geral') ?></div>
<?php } elseif (count($erros) > 0) { ?>
    <div class="mensagem erro" role="alert">Corrija os campos indicados abaixo.</div>
<?php } ?>
    <form id="form-login" method="post" action="login.php" onsubmit="return validarLogin()" novalidate>
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
        <button type="submit" class="botao">Entrar</button>
    </form>
    <p>Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
</div>
<?php
rodape();
