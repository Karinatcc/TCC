<?php
session_start();
require 'database/conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
    exit;
}

$empresa_id = $_SESSION['usuario_id'];
$cliente_id = $_GET['cliente_id'] ?? '';

if (!$cliente_id) {
    echo json_encode(['success' => false, 'error' => 'Cliente não especificado']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT m.*, 
               CASE 
                 WHEN m.enviado_por IS NULL THEN 'Cliente'
                 WHEN m.enviado_por = ? THEN 'Você'
                 ELSE 'Sistema'
               END as nome_exibicao
        FROM mensagens m
        WHERE m.empresa_id = ? AND m.cliente_id = ?
        ORDER BY m.criado_em ASC
    ");
    
    $stmt->execute([$empresa_id, $empresa_id, $cliente_id]);
    $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'cliente_id' => $cliente_id,
        'mensagens' => $mensagens
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>