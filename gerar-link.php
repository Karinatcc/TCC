<?php
session_start();
require 'database/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Usuário não está logado");
}

$usuarioId = $_SESSION['usuario_id'];

echo "<a href='cadastro-cliente.php?empresa_id=$usuarioId'>Abrir página</a>";
