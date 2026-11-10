<?php
// Ponto de entrada: manda o visitante para o início (logado) ou para o login
require 'funcoes.php';

if (esta_logado()) {
    header('Location: inicio.php');
} else {
    header('Location: login.php');
}
exit;
