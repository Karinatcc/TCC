<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html?mensagem=Faça+login+para+acessar+esta+funcionalidade&tipo=erro');
    exit;
}

require './database/conexao.php';

$usuario_id = $_SESSION['usuario_id'];

try {
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id]);

    session_destroy();

    header('Location: html/conta-excluida.html');
    exit;

} catch (PDOException $e) {
    header('Location: chatt.php?mensagem=Erro+ao+excluir+conta&tipo=erro');
    exit;
}
?>