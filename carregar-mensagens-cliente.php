<?php
// carregar-mensagens-cliente.php - Carrega mensagens de um cliente específico
session_start();
require 'database/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    die(json_encode(['success' => false, 'error' => 'Não autorizado']));
}

$empresa_id = $_SESSION['usuario_id'];
$cliente_id = $_GET['cliente_id'] ?? '';

if (!$cliente_id) {
    die(json_encode(['success' => false, 'error' => 'Cliente não especificado']));
}

try {
    // Buscar mensagens deste cliente com a empresa
    // Cliente envia: enviado_por = NULL
    // Dono envia: enviado_por = empresa_id
    $stmt = $pdo->prepare("
        SELECT 
            m.id,
            m.conteudo,
            m.enviado_por,
            m.criado_em,
            CASE 
                WHEN m.enviado_por IS NULL THEN 'Cliente'
                WHEN m.enviado_por = ? THEN 'Você'
                ELSE 'Sistema'
            END as nome_exibicao
        FROM mensagens m
        WHERE m.empresa_id = ? 
        AND (
            -- Mensagens do cliente (enviado_por = NULL)
            (m.enviado_por IS NULL)
            OR 
            -- Mensagens do dono para este cliente
            (m.enviado_por = ?)
        )
        ORDER BY m.criado_em ASC
    ");
    
    $stmt->execute([$empresa_id, $empresa_id, $empresa_id]);
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