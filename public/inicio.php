<?php
// Página inicial (área interna)
require __DIR__ . '/../funcoes.php';
exigir_login();

// Até 5 eventos que ainda vão acontecer
$proximos = proximos_eventos(5);

cabecalho('Início');
?>
<div class="card">
    <h1>Bem-vindo(a), <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h1>
    <p>Veja os próximos eventos ou crie um novo. Use o menu acima para navegar.</p>
    <p>
        <a class="botao" href="eventos.php">Ver todos os eventos</a>
        <a class="botao" href="logout.php">Sair</a>
    </p>
</div>

<div class="card">
    <h2>Próximos eventos</h2>
<?php if (count($proximos) === 0) { ?>
    <p>Nenhum evento futuro cadastrado ainda.</p>
<?php } else { ?>
    <ul class="lista-eventos">
<?php foreach ($proximos as $evento) { ?>
        <li class="evento-item">
            <div>
                <strong><?= htmlspecialchars($evento['nome']) ?></strong><br>
                <?= htmlspecialchars(formatar_data($evento['data'])) ?> às <?= htmlspecialchars($evento['horario']) ?>
                - <?= htmlspecialchars($evento['local']) ?>
            </div>
            <a class="botao" href="detalhes.php?id=<?= (int) $evento['id'] ?>">Detalhes</a>
        </li>
<?php } ?>
    </ul>
<?php } ?>
</div>
<?php
rodape();
