<?php
// Ponto de entrada: manda o visitante para o início (logado) ou para o login

// O servidor embutido (php -S) manda para cá qualquer endereço que não existe
// em public/ (ex.: /dados/usuarios.json). Só "/" e "/index.php" são válidos;
// o resto responde 404.
$caminho = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($caminho !== '/' && $caminho !== '/index.php') {
    http_response_code(404);
    echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Página não encontrada</title></head>';
    echo '<body><h1>404 - Página não encontrada</h1><p><a href="/login.php">Ir para o login</a></p></body></html>';
    exit;
}

require __DIR__ . '/../funcoes.php';

if (esta_logado()) {
    header('Location: inicio.php');
} else {
    header('Location: login.php');
}
exit;
