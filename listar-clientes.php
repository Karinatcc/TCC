<?php
session_start();
header('Content-Type: application/json');

// VERIFICAR LOGIN (somente empresa/dono acessa este arquivo)
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Usuário não autenticado. Faça login.'
    ]);
    exit;
}

require 'database/conexao.php';

// empresa_id SEMPRE é o id do usuário logado
$empresa_id = $_SESSION['usuario_id'];

$status = $_GET['status'] ?? 'aguardando';

try {

    // Agora busca na tabela REAL "clientes"
    $stmt = $pdo->prepare("
        SELECT 
            id AS cliente_id,
            nome,
            status,
            ultima_atividade
        FROM clientes
        WHERE empresa_id = ? AND status = ?
        ORDER BY ultima_atividade DESC
    ");

    $stmt->execute([$empresa_id, $status]);
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'clientes' => $clientes
    ]);
} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
