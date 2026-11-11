<?php
// Lista de todos os eventos (área interna)
require 'funcoes.php';
exigir_login();

// Eventos ordenados por data e horário
$eventos = ler_json(ARQ_EVENTOS);
usort($eventos, 'comparar_eventos');

cabecalho('Eventos');
?>
<div class="card">
    <h1>Eventos</h1>
    <p><a class="botao" href="criar-evento.php">Criar novo evento</a></p>
<?php if (count($eventos) === 0) { ?>
    <p>Nenhum evento cadastrado ainda.</p>
<?php } else { ?>
    <ul class="lista-eventos">
<?php foreach ($eventos as $evento) { ?>
        <li class="evento-item">
            <div>
                <strong><?= htmlspecialchars($evento['nome']) ?></strong><br>
                Data: <?= htmlspecialchars(formatar_data($evento['data'])) ?>
                | Horário: <?= htmlspecialchars($evento['horario']) ?><br>
                Local: <?= htmlspecialchars($evento['local']) ?>
            </div>
            <a class="botao" href="detalhes.php?id=<?= (int) $evento['id'] ?>">Detalhes</a>
        </li>
<?php } ?>
    </ul>
<?php } ?>
</div>
<?php
rodape();
