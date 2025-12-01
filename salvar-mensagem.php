<?php
session_start();
require 'database/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não logado']);
    exit;
}

if ($_POST) {
    $mensagem = trim($_POST['mensagem'] ?? '');
    $usuario_id = $_SESSION['usuario_id'];
    
    if (!empty($mensagem)) {
        try {
            // Dono envia: enviado_por = usuario_id, empresa_id = usuario_id
            $sql = "INSERT INTO mensagens (conteudo, enviado_por, empresa_id) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$mensagem, $usuario_id, $usuario_id]);
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
?>