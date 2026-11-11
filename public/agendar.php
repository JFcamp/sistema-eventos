<?php
// Agenda a participação do usuário logado em um evento
require __DIR__ . '/../funcoes.php';
exigir_login();

// Só aceita envio de formulário (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: eventos.php');
    exit;
}

$evento_id = (int) ($_POST['evento_id'] ?? 0);
$usuario_id = $_SESSION['usuario_id'];

// O evento precisa existir
if (buscar_evento($evento_id) === null) {
    definir_mensagem('erro', 'Evento não encontrado.');
    header('Location: eventos.php');
    exit;
}

// Impede o mesmo usuário de agendar o mesmo evento duas vezes
if (ja_agendado($usuario_id, $evento_id)) {
    definir_mensagem('erro', 'Você já agendou este evento.');
    header('Location: detalhes.php?id=' . $evento_id);
    exit;
}

// Salva o agendamento
$agendamentos = ler_json(ARQ_AGENDAMENTOS);
$agendamentos[] = [
    'id' => proximo_id($agendamentos),
    'usuario_id' => $usuario_id,
    'evento_id' => $evento_id,
    'criado_em' => date('Y-m-d H:i:s'),
];
salvar_json(ARQ_AGENDAMENTOS, $agendamentos);

definir_mensagem('sucesso', 'Agendamento realizado com sucesso!');
header('Location: detalhes.php?id=' . $evento_id);
exit;
