<?php
session_start();
header('Content-Type: application/json');
require 'database/conexao.php';

// Verifica autenticação
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
    exit;
}

// Dados
$empresa_id = $_SESSION['usuario_id'];
$cliente_id = $_GET['cliente_id'] ?? null;

if (!$cliente_id) {
    echo json_encode(['success' => false, 'error' => 'Cliente não informado']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            m.id,
            m.conteudo,
            m.enviado_por,
            m.criado_em,
            m.empresa_id,
            m.cliente_id
        FROM mensagens m
        WHERE m.empresa_id = ? 
          AND m.cliente_id = ?
        ORDER BY m.criado_em ASC
    ");

    $stmt->execute([$empresa_id, $cliente_id]);
    $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'mensagens' => $mensagens
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
