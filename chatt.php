<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html?mensagem=Faça+login+para+acessar+o+chat&tipo=erro');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatNexus</title>
    <link rel="stylesheet" href="css/chatt.css">
    <link rel="stylesheet" href="css/configuracoes.css">
    <link rel="stylesheet" href="css/sidebar-simples.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rowdies:wght@300;400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="app">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebarContainer">
        <header class="sidebar-header">
            <h3>Clientes</h3>

            <div class="tabs">
                <button class="tab active" data-status="atendidos">Atendidos</button>
                <button class="tab" data-status="aguardando">Aguardando</button>
            </div>
        </header>

        <p class="no-chats" id="statusText">Nenhum cliente disponível.</p>
    </aside>

    <div id="typingIndicator" class="typing" style="display:none;">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>

    <!-- CHAT AREA -->
    <main class="chat">
        <header class="chat-header" id="chatHeader" style="position: relative;">
            <h4 class="chat-title" id="chatName">Selecione um cliente</h4>
            
            <!-- BOTÃO DE CONFIGURAÇÕES -->
            <button class="config-btn" id="configBtn">
                <img src="imagem/config.png" alt="Configurações">
            </button>
            
            <!-- MENU DE CONFIGURAÇÕES -->
            <!-- MENU DE CONFIGURAÇÕES -->
            <div class="config-menu" id="configMenu" style="display: none;">
                <h4>Configurações</h4>
                <div class="config-divider"></div>
                
                <!-- BOTÃO MEU LINK DE ATENDIMENTO - ADICIONAR ESTE -->
                <button class="link-option" onclick="window.location.href='meu-link.php'">
                    <svg fill="currentColor" viewBox="0 0 24 24" width="16" height="16">
                        <path d="M17 7h-4v2h4c1.65 0 3 1.35 3 3s-1.35 3-3 3h-4v2h4c2.76 0 5-2.24 5-5s-2.24-5-5-5zm-6 8H7c-1.65 0-3-1.35-3-3s1.35-3 3-3h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-2zm-3-4h8v2H8z"/>
                    </svg>
                    Meu Link de Atendimento
                </button>
                <div class="config-divider"></div>
                <!-- FIM DO BOTÃO ADICIONADO -->
                
                <button class="delete-option" id="deleteAccountBtn">
                    <svg fill="#dc2626" viewBox="0 0 24 24">
                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                    </svg>
                    Excluir Conta
                </button>
            </div>
        </header>

        <div class="messages" id="messages">
            <div class="msg received">Olá! Tudo bem?</div>
            <div class="msg sent">Olá! Tudo sim, e com você?<br>Como posso ajudar?</div>
        </div>

        <footer class="chat-input">
            <button class="icon"></button>
            <input type="text" id="msgInput" placeholder="Digite sua mensagem...">
            <button id="sendBtn" class="send">Enviar</button>
        </footer>
    </main>

</div>

<!-- MODAL DE CONFIRMAÇÃO DE EXCLUSÃO -->
<div class="modal-overlay" id="deleteModal" style="display: none;">
    <div class="modal-content">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
        </div>
        <h3>Excluir Conta Permanentemente</h3>
        <p>Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita e todos os seus dados serão permanentemente removidos do nosso sistema.</p>
        <div class="modal-actions">
            <button class="btn-cancel" id="cancelDelete">Cancelar</button>
            <button class="btn-confirm-delete" id="confirmDelete">Excluir Conta</button>
        </div>
    </div>
</div>

<script src="js/sidebar-simples.js"></script>
<script src="js/chat.js"></script>
<script src="js/configuracoes.js"></script>
</body>
</html>