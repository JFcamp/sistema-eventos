<?php
// Cancela um agendamento do usuário logado
require __DIR__ . '/../funcoes.php';
exigir_login();

// Só aceita envio de formulário (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: meus-agendamentos.php');
    exit;
}

$agendamento_id = (int) ($_POST['agendamento_id'] ?? 0);
$agendamentos = ler_json(ARQ_AGENDAMENTOS);
$restantes = [];
$removeu = false;

// Mantém todos, menos o agendamento pedido (e só se for do usuário logado)
foreach ($agendamentos as $agendamento) {
    if ($agendamento['id'] === $agendamento_id && $agendamento['usuario_id'] === $_SESSION['usuario_id']) {
        $removeu = true;
    } else {
        $restantes[] = $agendamento;
    }
}

if ($removeu) {
    salvar_json(ARQ_AGENDAMENTOS, $restantes);
    definir_mensagem('sucesso', 'Agendamento cancelado.');
} else {
    definir_mensagem('erro', 'Agendamento não encontrado.');
}

header('Location: meus-agendamentos.php');
exit;
