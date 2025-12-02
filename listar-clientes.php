<?php
// listar-clientes.php - VERSÃO CORRIGIDA
session_start();
header('Content-Type: application/json');

// VERIFICAR LOGIN
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Usuário não autenticado. Faça login.'
    ]);
    exit;
}

require 'database/conexao.php';

$empresa_id = $_SESSION['usuario_id'];
$status = $_GET['status'] ?? 'aguardando';

try {
    $stmt = $pdo->prepare("
        SELECT cliente_id, status, ultima_atividade 
        FROM clientes_online 
        WHERE empresa_id = ? AND status = ?
        ORDER BY ultima_atividade DESC
    ");
    
    $stmt->execute([$empresa_id, $status]);
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // TESTE: Adicionar clientes fake se estiver vazio
    if (empty($clientes) && $status === 'aguardando') {
        $clientes = [
            [
                'cliente_id' => 'cli_test_' . rand(1000, 9999),
                'status' => 'aguardando',
                'ultima_atividade' => date('Y-m-d H:i:s'),
                'nome' => 'Cliente Teste ' . rand(1, 100)
            ]
        ];
    }
    
    // Formatar nomes
    foreach ($clientes as &$cliente) {
        $cliente['nome'] = 'Cliente ' . substr($cliente['cliente_id'], 4, 6);
    }
    
    echo json_encode([
        'success' => true,
        'clientes' => $clientes,
        'teste' => 'Dados de teste'  // Para debug
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>