<?php
session_start();
require 'database/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

// Buscar link único do usuário
$stmt = $pdo->prepare("SELECT link_unico, nome FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch();

$link_completo = "http://localhost/tcc/chat-cliente.php?empresa=" . $usuario['link_unico'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Link - ChatNexus</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/meu-link.css">
</head>
<body>
    <div class="container">
        <h2>Seu Link de Atendimento</h2>
        <p>Copie este link e cole no seu site e/ou nas redes sociais da empresa para que seus clientes possam entrar em contato com você:</p>
        
        <div class="link-box" id="linkBox">
            <?php echo $link_completo; ?>
        </div>
        
        <div class="btn-container">
            <button class="copy-btn" onclick="copiarLink()">📋 Copiar Link</button>
            <a href="chatt.php" class="back-btn">← Voltar ao Chat</a>
        </div>
        
        <p id="copiadoMsg">Link copiado para a área de transferência!</p>
        
        <div class="dica-box">
            <h3>Como usar seu link:</h3>
            <p><strong>1. Redes sociais:</strong> Cole na biografia do perfil</p>
            <p><strong>2. Site:</strong> Adicione como botão "Fale Conosco"</p>
            <p><strong>3. E-mail:</strong> Inclua na sua assinatura</p>
        </div>

        <div class="dica-extra">
            <p><strong>Dica:</strong> Quando um cliente clicar no link, ele será direcionado diretamente para o chat com você!</p>
        </div>
    </div>

    <script>
    function copiarLink() {
        const link = document.getElementById('linkBox').textContent;
        navigator.clipboard.writeText(link).then(() => {
            const msg = document.getElementById('copiadoMsg');
            msg.style.display = 'block';
            setTimeout(() => {
                msg.style.display = 'none';
            }, 3000);
        }).catch(err => {
            alert('Erro ao copiar: ' + err);
        });
    }

    // Também permite copiar clicando no link-box
    document.getElementById('linkBox').addEventListener('click', function() {
        copiarLink();
    });
    </script>
</body>
</html>