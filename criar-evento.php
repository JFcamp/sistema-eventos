<?php
// Cadastro de um novo evento (área interna)
require 'funcoes.php';
exigir_login();

$erros = [];
$nome = '';
$descricao = '';
$data = '';
$horario = '';
$local = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $data = trim($_POST['data'] ?? '');
    $horario = trim($_POST['horario'] ?? '');
    $local = trim($_POST['local'] ?? '');

    // Mesmas validações do JavaScript
    $erros = validar_evento($nome, $descricao, $data, $horario, $local);

    // Sem erros: salva o evento
    if (count($erros) === 0) {
        $eventos = ler_json(ARQ_EVENTOS);
        $eventos[] = [
            'id' => proximo_id($eventos),
            'nome' => $nome,
            'descricao' => $descricao,
            'data' => $data,
            'horario' => $horario,
            'local' => $local,
            'criador_id' => $_SESSION['usuario_id'],
        ];
        salvar_json(ARQ_EVENTOS, $eventos);
        definir_mensagem('sucesso', 'Evento criado com sucesso!');
        header('Location: eventos.php');
        exit;
    }
}

cabecalho('Criar evento');
?>
<div class="card formulario">
    <h1>Criar evento</h1>
    <form id="form-evento" method="post" action="criar-evento.php" onsubmit="return validarEvento()" novalidate>
        <div class="campo">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" maxlength="100" value="<?= htmlspecialchars($nome) ?>">
            <span class="erro-campo" id="erro-nome"><?= texto_erro($erros, 'nome') ?></span>
        </div>
        <div class="campo">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" rows="4" maxlength="500"><?= htmlspecialchars($descricao) ?></textarea>
            <span class="erro-campo" id="erro-descricao"><?= texto_erro($erros, 'descricao') ?></span>
        </div>
        <div class="campo">
            <label for="data">Data</label>
            <input type="date" id="data" name="data" value="<?= htmlspecialchars($data) ?>">
            <span class="erro-campo" id="erro-data"><?= texto_erro($erros, 'data') ?></span>
        </div>
        <div class="campo">
            <label for="horario">Horário</label>
            <input type="time" id="horario" name="horario" value="<?= htmlspecialchars($horario) ?>">
            <span class="erro-campo" id="erro-horario"><?= texto_erro($erros, 'horario') ?></span>
        </div>
        <div class="campo">
            <label for="local">Local</label>
            <input type="text" id="local" name="local" maxlength="100" value="<?= htmlspecialchars($local) ?>">
            <span class="erro-campo" id="erro-local"><?= texto_erro($erros, 'local') ?></span>
        </div>
        <button type="submit" class="botao">Criar evento</button>
    </form>
</div>
<?php
rodape();
