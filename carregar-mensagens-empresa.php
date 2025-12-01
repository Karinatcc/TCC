<?php
session_start();
require 'database/conexao.php';

// Verificar qual empresa carregar
if (isset($_SESSION['empresa_id'])) {
    // Modo cliente (acesso via link) ou dono logado como empresa
    $empresa_id = $_SESSION['empresa_id'];
} elseif (isset($_SESSION['usuario_id'])) {
    // Modo dono logado
    $empresa_id = $_SESSION['usuario_id'];
} else {
    die(json_encode(['success' => false, 'error' => 'Não autorizado']));
}

try {
    // Buscar todas mensagens desta empresa
    $stmt = $pdo->prepare("
        SELECT m.*, 
                CASE 
                    WHEN m.enviado_por IS NULL THEN 'Cliente'  <!-- CORRETO! -->
                    ELSE u.nome 
                END as nome_exibicao
        FROM mensagens m 
        LEFT JOIN usuarios u ON m.enviado_por = u.id 
        WHERE m.empresa_id = ?
        ORDER BY m.criado_em ASC
    ");
    $stmt->execute([$empresa_id]);
    $mensagens = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true, 
        'mensagens' => $mensagens, 
        'empresa_id' => $empresa_id,
        'usuario_id' => isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>