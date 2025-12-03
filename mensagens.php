<?php
session_start();
require './database/conexao.php';

// PEGANDO PARAMETROS DA URL
$cliente_id = $_GET['cliente_id'] ?? '';
$empresa_id = $_GET['empresa_id'] ?? '';

// SE NÃO TIVER CLIENTE, NÃO ABRE O CHAT
if (!$cliente_id || !$empresa_id) {
    die("Parâmetros inválidos.");
}

// ------------------------------
// SALVAR MENSAGEM
// ------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conteudo = trim($_POST['conteudo'] ?? '');

    if ($conteudo !== '') {

        // enviado_por = 0 → cliente
        $enviado_por = $cliente_id;

        $query = "INSERT INTO mensagens (conteudo, enviado_por, empresa_id, cliente_id)
                  VALUES (?, ?, ?, ?)";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$conteudo, $enviado_por, $empresa_id, $cliente_id]);
    }

    // Evita reenvio do formulário
    header("Location: mensagens.php?cliente_id=$cliente_id&empresa_id=$empresa_id");
    exit;
}

// ------------------------------
// LISTAR MENSAGENS
// ------------------------------
$query = "SELECT * FROM mensagens 
          WHERE cliente_id = ? AND empresa_id = ?
          ORDER BY criado_em ASC";

$stmt = $pdo->prepare($query);
$stmt->execute([$cliente_id, $empresa_id]);
$mensagens = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
        }

        #chat-box {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
        }

        .msg {
            margin-bottom: 12px;
            padding: 10px 14px;
            border-radius: 15px;
            max-width: 80%;
            word-wrap: break-word;
            display: inline-block;
        }

        .cliente {
            background: #e1ffc7;
            text-align: left;
            align-self: flex-start;
            margin-right: auto;
            border-bottom-left-radius: 0;
        }

        .empresa {
            background: #c7d7ff;
            text-align: right;
            align-self: flex-end;
            margin-left: auto;
            border-bottom-right-radius: 0;
        }


        form {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        input[type=text] {
            flex: 1;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #aaa;
        }

        button {
            padding: 10px 15px;
            border: none;
            background: #4CAF50;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div id="chat-box">
        <h2>Chat com a Empresa</h2>

        <?php foreach ($mensagens as $m): ?>
            <div class="msg <?= ($m['enviado_por'] == $cliente_id ? 'cliente' : 'empresa') ?>">
                <?= htmlspecialchars($m['conteudo']) ?><br>
                <small><?= $m['criado_em'] ?></small>
            </div>
        <?php endforeach; ?>


        <form action="" method="POST">
            <input type="text" name="conteudo" placeholder="Digite sua mensagem..." required>
            <button type="submit">Enviar</button>
        </form>
    </div>

</body>

</html>