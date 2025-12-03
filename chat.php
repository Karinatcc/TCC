<?php
session_start();
require 'database/conexao.php';

// Verifica cliente logado
if (!isset($_SESSION['cliente_id'])) {
    die('Acesso negado: cliente não identificado.');
}

$cliente_id = $_SESSION['cliente_id'];

// Carrega mensagens anteriores
$query = "SELECT * FROM mensagens WHERE cliente_id = ? ORDER BY id ASC";
$stmt = $conexao->prepare($query);
$stmt->bind_param('i', $cliente_id);
$stmt->execute();
$result = $stmt->get_result();
$mensagens = [];
while ($row = $result->fetch_assoc()) {
    $mensagens[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat do Cliente</title>
    <style>
        body {
            font-family: Arial;
        }

        #chat-box {
            width: 100%;
            height: 350px;
            border: 1px solid #aaa;
            padding: 10px;
            overflow-y: auto;
            background: #f8f8f8;
        }

        .msg-cliente {
            text-align: right;
            color: #000;
            margin-bottom: 8px;
        }

        .msg-admin {
            text-align: left;
            color: #444;
            margin-bottom: 8px;
        }
    </style>
</head>

<body>

    <h3>Atendimento</h3>

    <div id="chat-box">
        <?php foreach ($mensagens as $m): ?>
            <div class="<?= $m['quem'] === 'cliente' ? 'msg-cliente' : 'msg-admin' ?>">
                <strong><?= $m['quem'] ?>:</strong> <?= htmlspecialchars($m['mensagem']) ?>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="post" id="formEnviar">
        <input type="text" id="mensagem" name="mensagem" placeholder="Digite sua mensagem..." style="width: 80%;">
        <button type="submit">Enviar</button>
    </form>

    <script>
        // Enviar mensagem via AJAX
        const form = document.getElementById('formEnviar');
        const chatBox = document.getElementById('chat-box');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            let texto = document.getElementById('mensagem').value.trim();

            if (texto === '') return;

            let dados = new FormData();
            dados.append('mensagem', texto);

            const response = await fetch('salvar_mensagem.php', {
                method: 'POST',
                body: dados
            });

            const resultado = await response.json();

            if (resultado.success) {
                chatBox.innerHTML += `<div class='msg-cliente'><strong>cliente:</strong> ${texto}</div>`;
                document.getElementById('mensagem').value = '';
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });

        // Atualizar mensagens a cada 3 segundos
        setInterval(async () => {
            const response = await fetch('listar_mensagens.php');
            const html = await response.text();
            document.getElementById('chat-box').innerHTML = html;
            chatBox.scrollTop = chatBox.scrollHeight;
        }, 3000);
    </script>

</body>

</html>