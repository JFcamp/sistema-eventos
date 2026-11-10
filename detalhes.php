<?php
// Detalhes de um evento (área interna)
require 'funcoes.php';
exigir_login();

// O id vem pela URL: detalhes.php?id=1
$id = (int) ($_GET['id'] ?? 0);
$evento = buscar_evento($id);

if ($evento === null) {
    definir_mensagem('erro', 'Evento não encontrado.');
    header('Location: eventos.php');
    exit;
}

// Descobre o nome de quem criou o evento
$criador = 'Desconhecido';
foreach (ler_json(ARQ_USUARIOS) as $usuario) {
    if ($usuario['id'] === $evento['criador_id']) {
        $criador = $usuario['nome'];
    }
}

$agendado = ja_agendado($_SESSION['usuario_id'], $evento['id']);

cabecalho('Detalhes do evento');
?>
<div class="card">
    <h1><?= htmlspecialchars($evento['nome']) ?></h1>
    <dl class="detalhes">
        <dt>Descrição</dt>
        <dd><?= nl2br(htmlspecialchars($evento['descricao'])) ?></dd>
        <dt>Data</dt>
        <dd><?= htmlspecialchars(formatar_data($evento['data'])) ?></dd>
        <dt>Horário</dt>
        <dd><?= htmlspecialchars($evento['horario']) ?></dd>
        <dt>Local</dt>
        <dd><?= htmlspecialchars($evento['local']) ?></dd>
        <dt>Criado por</dt>
        <dd><?= htmlspecialchars($criador) ?></dd>
    </dl>

<?php if ($agendado) { ?>
    <p><strong>Você já está agendado neste evento.</strong></p>
    <a class="botao" href="meus-agendamentos.php">Ver meus agendamentos</a>
<?php } else { ?>
    <form method="post" action="agendar.php">
        <input type="hidden" name="evento_id" value="<?= (int) $evento['id'] ?>">
        <button type="submit" class="botao">Agendar</button>
    </form>
<?php } ?>
    <p><a href="eventos.php">Voltar para os eventos</a></p>
</div>
<?php
rodape();
