<?php
// chat-cliente.php - Sistema de Chat usando seu banco existente
session_start();
require 'database/conexao.php';

// VERIFICAR SE É ACESSO VIA LINK DA EMPRESA
$empresa_link = $_GET['empresa'] ?? '';

if ($empresa_link) {
    // MODO CLIENTE - Acesso via link da empresa
    $stmt = $pdo->prepare("SELECT id, nome FROM usuarios WHERE link_unico = ?");
    $stmt->execute([$empresa_link]);
    $empresa = $stmt->fetch();
    
    if (!$empresa) {
        die("❌ Link de empresa inválido ou não encontrado");
    }
    
    $_SESSION['empresa_id'] = $empresa['id'];
    $_SESSION['empresa_nome'] = $empresa['nome'];
    $_SESSION['modo_cliente'] = true;
    
} else {
    // MODO NORMAL - Usuário logado (acesso direto)
    if (!isset($_SESSION['usuario_id'])) {
        die("❌ Faça login primeiro! <a href='login.html'>Login</a>");
    }
    $_SESSION['modo_cliente'] = false;
    $_SESSION['empresa_id'] = $_SESSION['usuario_id'];
}

// ============================================
// REGISTRAR CLIENTE QUANDO ACESSAR VIA LINK
// ============================================
if ($_SESSION['modo_cliente']) {
    try {
        // Gerar ID único para o cliente
        $cliente_id = 'cli_' . md5($_SERVER['REMOTE_ADDR'] . time() . rand(1000, 9999));
        
        // Registrar cliente online
        $stmt = $pdo->prepare("
            INSERT OR REPLACE INTO clientes_online 
            (empresa_id, cliente_id, status, ultima_atividade) 
            VALUES (?, ?, 'aguardando', CURRENT_TIMESTAMP)
        ");
        
        $stmt->execute([$_SESSION['empresa_id'], $cliente_id]);
        
        $_SESSION['cliente_id'] = $cliente_id;
        
    } catch (Exception $e) {
        // Silenciar erro para não afetar o chat
        error_log("Erro ao registrar cliente: " . $e->getMessage());
    }
}
// ============================================

// AÇÕES VIA AJAX
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    switch ($_GET['action']) {
        case 'enviar':
            $mensagem = trim($_POST['mensagem'] ?? '');
            if (!empty($mensagem)) {
                try {
                    // Cliente envia: enviado_por = NULL, empresa_id = empresa_do_link
                    $stmt = $pdo->prepare("INSERT INTO mensagens (conteudo, enviado_por, empresa_id) VALUES (?, NULL, ?)");
                    $stmt->execute([$mensagem, $_SESSION['empresa_id']]);
                    
                    // Atualizar última atividade do cliente
                    if (isset($_SESSION['cliente_id'])) {
                        $stmt_atividade = $pdo->prepare("
                            UPDATE clientes_online 
                            SET ultima_atividade = CURRENT_TIMESTAMP 
                            WHERE cliente_id = ?
                        ");
                        $stmt_atividade->execute([$_SESSION['cliente_id']]);
                    }
                    
                    echo json_encode(['success' => true]);
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                }
            }
            break;
            
        case 'carregar':
            // Redirecionar para o arquivo comum
            require 'carregar-mensagens-empresa.php';
            exit;
            break;
    }
    exit;
}

// Obter dados para exibir
if ($_SESSION['modo_cliente']) {
    $nome_exibicao = $_SESSION['empresa_nome'];
    $usuario_id = $_SESSION['empresa_id'];
} else {
    $stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    $usuario = $stmt->fetch();
    $nome_exibicao = $usuario['nome'];
    $usuario_id = $_SESSION['usuario_id'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Cliente - <?php echo $nome_exibicao; ?></title>
    <style>
        *{ margin:0; padding:0; box-sizing:border-box; font-family:"Poppins", sans-serif; }
        body{ background:#e5ddd5; }
        .client-app{ width:100%; height:100vh; display:flex; flex-direction:column; background:#edf1f7; }
        
        /* HEADER */
        .client-header{ background:#f0f2f5; padding:15px; border-bottom:1px solid #ddd; }
        .client-header h3{ font-size:20px; font-weight:600; }
        .status{ font-size:13px; color:green; }
        
        /* MENSAGENS */
        .client-messages{ flex:1; padding:20px; overflow-y:auto; display:flex; flex-direction:column; gap:10px; }
        .msg{ padding:10px 14px; max-width:60%; border-radius:20px; font-size:14px; word-wrap:break-word; }
        .msg.sent{ background:#147aff; color:white; align-self:flex-end; }
        .msg.received{ background:#1c2a33; color:white; align-self:flex-start; }
        .msg-info{ font-size:11px; color:#666; margin-top:5px; }
        
        /* INPUT */
        .client-input{ display:flex; gap:10px; padding:15px; background:#e2e5ec; }
        .client-input input{ flex:1; padding:12px; border-radius:20px; border:2px solid #130f36cb; outline:none; }
        .client-input button{ background:#031842; border:none; color:white; padding:12px 20px; border-radius:20px; cursor:pointer; }
    </style>
</head>
<body>
    <div class="client-app">
        <div class="client-header">
            <h3>Chat Cliente - <?php echo $nome_exibicao; ?></h3>
            <div class="status">● Online</div>
        </div>
        
        <div class="client-messages" id="mensagens">
            <!-- Mensagens carregadas via JavaScript -->
        </div>
        
        <div class="client-input">
            <input type="text" id="inputMensagem" placeholder="Digite sua mensagem...">
            <button onclick="enviarMensagem()">Enviar</button>
        </div>
    </div>

    <script>
        const usuarioId = <?php echo $usuario_id; ?>;
        
        // Carregar mensagens ao abrir
        carregarMensagens();
        
        // Enviar mensagem com Enter
        document.getElementById('inputMensagem').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') enviarMensagem();
        });
        
        // Atualizar mensagens a cada 1 segundo (tempo real)
        setInterval(carregarMensagens, 1000);
        
        function enviarMensagem() {
            const input = document.getElementById('inputMensagem');
            const mensagem = input.value.trim();
            
            if (mensagem) {
                fetch('chat-cliente.php?action=enviar', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'mensagem=' + encodeURIComponent(mensagem)
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        input.value = '';
                        carregarMensagens();
                    } else {
                        alert('Erro: ' + data.error);
                    }
                });
            }
        }
        
        function carregarMensagens() {
            fetch('carregar-mensagens-empresa.php')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const container = document.getElementById('mensagens');
                        container.innerHTML = '';
                        
                        data.mensagens.forEach(msg => {
                            const div = document.createElement('div');
                            // Se enviado_por é NULL é cliente (sent), senão é dono (received)
                            const isSent = msg.enviado_por === null;
                            
                            div.className = 'msg ' + (isSent ? 'sent' : 'received');
                            div.innerHTML = `
                                ${msg.conteudo}
                                <div class="msg-info">
                                    ${msg.nome_exibicao} • ${formatarData(msg.criado_em)}
                                </div>
                            `;
                            container.appendChild(div);
                        });
                        
                        container.scrollTop = container.scrollHeight;
                    }
                });
        }
        
        function formatarData(dataString) {
            const data = new Date(dataString);
            return data.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
        }
    </script>
</body>
</html>