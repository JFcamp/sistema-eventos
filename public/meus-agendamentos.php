<?php
// Lista os agendamentos do usuário logado (área interna)
require __DIR__ . '/../funcoes.php';
exigir_login();

$usuario_id = $_SESSION['usuario_id'];

// Junta cada agendamento do usuário com os dados do seu evento
$meus = [];
foreach (ler_json(ARQ_AGENDAMENTOS) as $agendamento) {
    if ($agendamento['usuario_id'] === $usuario_id) {
        $evento = buscar_evento($agendamento['evento_id']);
        if ($evento !== null) {
            $evento['agendamento_id'] = $agendamento['id'];
            $meus[] = $evento;
        }
    }
}
usort($meus, 'comparar_eventos');

cabecalho('Meus agendamentos');
?>
<div class="card">
    <h1>Meus agendamentos</h1>
<?php if (count($meus) === 0) { ?>
    <p>Você ainda não agendou nenhum evento. <a href="eventos.php">Ver eventos</a></p>
<?php } else { ?>
    <ul class="lista-eventos">
<?php foreach ($meus as $evento) { ?>
        <li class="evento-item">
            <div>
                <strong><?= htmlspecialchars($evento['nome']) ?></strong><br>
                <?= htmlspecialchars(formatar_data($evento['data'])) ?> às <?= htmlspecialchars($evento['horario']) ?><br>
                Local: <?= htmlspecialchars($evento['local']) ?>
            </div>
            <div class="acoes">
                <a class="botao" href="detalhes.php?id=<?= (int) $evento['id'] ?>">Detalhes</a>
                <form method="post" action="cancelar.php" onsubmit="return confirmarCancelamento()">
                    <input type="hidden" name="agendamento_id" value="<?= (int) $evento['agendamento_id'] ?>">
                    <button type="submit" class="botao botao-perigo">Cancelar</button>
                </form>
            </div>
        </li>
<?php } ?>
    </ul>
<?php } ?>
</div>
<?php
rodape();
