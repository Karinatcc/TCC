<?php
session_start();
require 'database/conexao.php';

/*
---------------------------------------------
 DEFINIÇÃO CORRETA DO EMPRESA_ID
---------------------------------------------
Regra:
- Se for dono logado → empresa_id = $_SESSION['empresa_id']
- Se for cliente acessando via link → empresa_id ainda está salvo na sessão
*/

if (!isset($_SESSION['empresa_id'])) {
    echo json_encode(['success' => false, 'error' => 'Empresa não identificada']);
    exit;
}

$empresa_id = $_SESSION['empresa_id'];

// Verifica se é modo cliente
$is_modo_cliente = isset($_SESSION['modo_cliente']) && $_SESSION['modo_cliente'] === true;

try {
    // Buscar todas mensagens relacionadas a esta empresa
    $stmt = $pdo->prepare("
        SELECT 
            m.*,
            CASE 
                WHEN m.enviado_por IS NULL THEN 'Cliente'
                ELSE u.nome 
            END AS nome_exibicao
        FROM mensagens m
        LEFT JOIN usuarios u ON m.enviado_por = u.id
        WHERE m.empresa_id = ?
        ORDER BY m.criado_em ASC
    ");

    $stmt->execute([$empresa_id]);
    $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'mensagens' => $mensagens,
        'empresa_id' => $empresa_id,
        'is_modo_cliente' => $is_modo_cliente,
        'usuario_id' => $_SESSION['usuario_id'] ?? 0
    ]);
} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
