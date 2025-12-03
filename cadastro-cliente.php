<?php
session_start();
require './database/conexao.php';

// empresa_id vem pela URL (GET)
$empresa_id = $_GET['empresa_id'] ?? '';

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitização básica
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $empresa_id = trim($_POST['empresa_id'] ?? $empresa_id);

    // Validação simples
    if ($nome === '' || $telefone === '') {
        $erro = "Preencha todos os campos!";
    } else {

        $query = "INSERT INTO clientes (nome, telefone, empresa_id) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);

        try {
            $stmt->execute([$nome, $telefone, $empresa_id]);

            // Pega ID inserido
            $cliente_id = $pdo->lastInsertId();

            // Salva na sessão
            $_SESSION['cliente_id'] = $cliente_id;

            // Redireciona para o chat
            header("Location: mensagens.php?cliente_id=$cliente_id&empresa_id=$empresa_id");
            exit;
        } catch (PDOException $e) {
            $erro = "Erro ao salvar no banco.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Cliente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            height: 100vh;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #49a09d, #5f2c82);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: white;
            padding: 40px 30px;
            border-radius: 16px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .card h2 {
            margin-bottom: 8px;
            color: #333;
        }

        .card p {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .input-group {
            margin-bottom: 18px;
            text-align: left;
        }

        .input-group label {
            font-size: 13px;
            color: #555;
            display: block;
            margin-bottom: 6px;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 14px;
        }

        .input-group input:focus {
            border-color: #49a09d;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #5f2c82;
            color: white;
            font-size: 15px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.2s;
        }

        .btn:hover {
            background: #4a2266;
        }

        .erro {
            background: #ffe5e5;
            color: #c0392b;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 15px;
        }

        .rodape {
            margin-top: 18px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>

<body>

    <div class="card">
        <h2>Cadastro de Cliente</h2>
        <p>Preencha os dados para iniciar a conversa</p>

        <?php if (!empty($erro)) : ?>
            <div class="erro"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="input-group">
                <label>Nome</label>
                <input type="text" name="nome" placeholder="Digite seu nome" required>
            </div>

            <div class="input-group">
                <label>Telefone</label>
                <input type="text" name="telefone" placeholder="Digite seu telefone" required>
            </div>

            <input type="hidden" name="empresa_id" value="<?= htmlspecialchars($empresa_id) ?>">

            <button class="btn" type="submit">Entrar no Chat</button>
        </form>

        <div class="rodape">
            Atendimento rápido e seguro ✅
        </div>
    </div>

</body>

</html>