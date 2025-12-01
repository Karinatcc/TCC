<?php
// registrar-cliente.php
session_start();
require 'database/conexao.php';

if (!isset($_SESSION['empresa_id'])) {
    die(json_encode(['success' => false, 'error' => 'Não autorizado']));
}

// Gerar ID único para o cliente (IP + timestamp + random)
$cliente_id = 'cli_' . md5($_SERVER['REMOTE_ADDR'] . time() . rand(1000, 9999));

try {
    // Registrar cliente online
    $stmt = $pdo->prepare("
        INSERT OR REPLACE INTO clientes_online 
        (empresa_id, cliente_id, status, ultima_atividade) 
        VALUES (?, ?, 'aguardando', CURRENT_TIMESTAMP)
    ");
    
    $stmt->execute([$_SESSION['empresa_id'], $cliente_id]);
    
    $_SESSION['cliente_id'] = $cliente_id;
    
    echo json_encode([
        'success' => true, 
        'cliente_id' => $cliente_id,
        'empresa_id' => $_SESSION['empresa_id']
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>