<?php
session_start();
require 'database/conexao.php';

header('Content-Type: application/json');

if (!isset($_POST['mensagem']) || empty(trim($_POST['mensagem']))) {
    echo json_encode(['success' => false, 'error' => 'Mensagem vazia']);
    exit;
}

$mensagem = trim($_POST['mensagem']);

// CASO 1: CLIENTE ENVIANDO
if (isset($_SESSION['modo_cliente']) && $_SESSION['modo_cliente'] === true) {
    $empresa_id = $_SESSION['empresa_id'] ?? 0;
    $cliente_id = $_SESSION['cliente_id'] ?? '';

    if (!$empresa_id || !$cliente_id) {
        echo json_encode(['success' => false, 'error' => 'Dados incompletos']);
        exit;
    }

    try {
        $sqlCreatedConversation = "INSERT INTO conversas (cliente_id, usuario_id) VALUES (?,?)";
        $stmtCreatedConversation = $pdo->prepare($sqlCreatedConversation);
        $stmtCreatedConversation->execute([$cliente_id, 1]);

        // 2. Obtém o ID da conversa recém-criada
        $conversa_id = $pdo->lastInsertId();

        $sql = "INSERT INTO mensagens (conteudo, enviado_por, empresa_id, cliente_id, conversa_id) VALUES (?, NULL, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$mensagem, $empresa_id, $cliente_id, $conversa_id]);

        // Atualiza atividade
        $stmt_atividade = $pdo->prepare("
            UPDATE clientes_online SET ultima_atividade = CURRENT_TIMESTAMP WHERE cliente_id = ?
        ");
        $stmt_atividade->execute([$cliente_id]);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// CASO 2: DONO ENVIANDO
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não logado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$cliente_id = $_POST['cliente_id'] ?? '';

if (!$cliente_id) {
    echo json_encode(['success' => false, 'error' => 'Selecione um cliente primeiro']);
    exit;
}

try {
    $sqlCreatedConversation2 = "INSERT INTO mensagens (conteudo, enviado_por, empresa_id, cliente_id) VALUES (?, ?, ?, ?)";


    $sql = "INSERT INTO mensagens (conteudo, enviado_por, empresa_id, cliente_id) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$mensagem, $usuario_id, $usuario_id, $cliente_id]);

    // Atualiza status
    $stmt_status = $pdo->prepare("
        UPDATE clientes_online SET status = 'atendido', ultima_atividade = CURRENT_TIMESTAMP 
        WHERE cliente_id = ? AND status = 'aguardando'
    ");
    $stmt_status->execute([$cliente_id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
