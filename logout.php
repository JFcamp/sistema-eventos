<?php
// Encerra a sessão do usuário
require 'funcoes.php';

// Remove os dados do usuário e guarda uma mensagem para a tela de login
unset($_SESSION['usuario_id']);
unset($_SESSION['usuario_nome']);
session_regenerate_id(true);
definir_mensagem('sucesso', 'Você saiu do sistema.');
header('Location: login.php');
exit;
