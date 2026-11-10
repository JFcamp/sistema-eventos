<?php
// Funções compartilhadas por todas as páginas do sistema.

// Inicia a sessão (necessária para guardar o usuário logado e as mensagens)
session_start();

// Fuso horário usado nas comparações de data
date_default_timezone_set('America/Sao_Paulo');

// Caminhos dos arquivos JSON onde os dados ficam salvos
define('ARQ_USUARIOS', __DIR__ . '/dados/usuarios.json');
define('ARQ_EVENTOS', __DIR__ . '/dados/eventos.json');
define('ARQ_AGENDAMENTOS', __DIR__ . '/dados/agendamentos.json');

// Lê um arquivo JSON e devolve um array (vazio se o arquivo não existir ou estiver inválido)
function ler_json($arquivo) {
    if (!file_exists($arquivo)) {
        return [];
    }
    $conteudo = file_get_contents($arquivo);
    $dados = json_decode($conteudo, true);
    if (!is_array($dados)) {
        return [];
    }
    return $dados;
}

// Grava um array em um arquivo JSON
function salvar_json($arquivo, $dados) {
    $conteudo = json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($arquivo, $conteudo, LOCK_EX);
}

// Devolve o próximo id disponível de uma lista (maior id + 1)
function proximo_id($lista) {
    $maior = 0;
    foreach ($lista as $item) {
        if ($item['id'] > $maior) {
            $maior = $item['id'];
        }
    }
    return $maior + 1;
}

// Verifica se existe um usuário logado
function esta_logado() {
    return isset($_SESSION['usuario_id']);
}

// Protege páginas internas: sem login, volta para a tela de login
function exigir_login() {
    if (!esta_logado()) {
        definir_mensagem('erro', 'Faça login para acessar essa página.');
        header('Location: login.php');
        exit;
    }
}

// Guarda uma mensagem na sessão (tipo: 'sucesso' ou 'erro')
function definir_mensagem($tipo, $texto) {
    $_SESSION['mensagem'] = ['tipo' => $tipo, 'texto' => $texto];
}

// Mostra a mensagem guardada (se houver) e a apaga em seguida
function exibir_mensagem() {
    if (isset($_SESSION['mensagem'])) {
        $tipo = $_SESSION['mensagem']['tipo'];
        $texto = $_SESSION['mensagem']['texto'];
        echo '<div class="mensagem ' . htmlspecialchars($tipo) . '">' . htmlspecialchars($texto) . '</div>';
        unset($_SESSION['mensagem']);
    }
}

// Devolve o texto de erro de um campo, já protegido com htmlspecialchars
function texto_erro($erros, $campo) {
    if (isset($erros[$campo])) {
        return htmlspecialchars($erros[$campo]);
    }
    return '';
}

// Escreve o início da página (head, menu se estiver logado e mensagens)
function cabecalho($titulo) {
    ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?> - Sistema de Eventos</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<?php if (esta_logado()) { ?>
    <nav class="menu">
        <ul>
            <li><a href="inicio.php">Início</a></li>
            <li><a href="eventos.php">Eventos</a></li>
            <li><a href="criar-evento.php">Criar evento</a></li>
            <li><a href="meus-agendamentos.php">Meus agendamentos</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>
<?php } else { ?>
    <header class="topo">Sistema de Agendamento de Eventos</header>
<?php } ?>
    <main class="container">
<?php
    exibir_mensagem();
}

// Escreve o final da página e carrega o JavaScript
function rodape() {
    ?>
    </main>
    <script src="js/scripts.js"></script>
</body>
</html>
<?php
}

// Converte uma data de 2026-11-10 para 10/11/2026
function formatar_data($data) {
    $partes = explode('-', $data);
    if (count($partes) !== 3) {
        return $data;
    }
    return $partes[2] . '/' . $partes[1] . '/' . $partes[0];
}

// Compara dois eventos por data e horário (usada pelo usort)
function comparar_eventos($a, $b) {
    return strcmp($a['data'] . ' ' . $a['horario'], $b['data'] . ' ' . $b['horario']);
}

// Devolve os próximos eventos (a partir de hoje), ordenados, limitados a $limite
function proximos_eventos($limite) {
    $hoje = date('Y-m-d');
    $proximos = [];
    foreach (ler_json(ARQ_EVENTOS) as $evento) {
        if ($evento['data'] >= $hoje) {
            $proximos[] = $evento;
        }
    }
    usort($proximos, 'comparar_eventos');
    return array_slice($proximos, 0, $limite);
}

// Valida os dados de um evento e devolve um array de erros (vazio se estiver tudo certo)
function validar_evento($nome, $descricao, $data, $horario, $local) {
    $erros = [];

    if ($nome === '') {
        $erros['nome'] = 'Informe o nome do evento.';
    } elseif (strlen($nome) > 100) {
        $erros['nome'] = 'O nome deve ter no máximo 100 caracteres.';
    }

    if ($descricao === '') {
        $erros['descricao'] = 'Informe a descrição.';
    } elseif (strlen($descricao) > 500) {
        $erros['descricao'] = 'A descrição deve ter no máximo 500 caracteres.';
    }

    // A data deve existir de verdade (checkdate) e não pode estar no passado
    $partes = explode('-', $data);
    if ($data === '') {
        $erros['data'] = 'Informe a data.';
    } elseif (count($partes) !== 3 || strlen($partes[0]) !== 4 || !checkdate((int) $partes[1], (int) $partes[2], (int) $partes[0])) {
        $erros['data'] = 'Informe uma data válida.';
    } elseif ($data < date('Y-m-d')) {
        $erros['data'] = 'A data não pode estar no passado.';
    }

    // Horário no formato HH:MM
    if ($horario === '') {
        $erros['horario'] = 'Informe o horário.';
    } elseif (!preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $horario)) {
        $erros['horario'] = 'Informe um horário válido.';
    }

    if ($local === '') {
        $erros['local'] = 'Informe o local.';
    } elseif (strlen($local) > 100) {
        $erros['local'] = 'O local deve ter no máximo 100 caracteres.';
    }

    return $erros;
}
