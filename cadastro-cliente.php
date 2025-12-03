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

        // Query correta
        $query = "INSERT INTO clientes (nome, telefone, empresa_id) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);

        try {
            $stmt->execute([$nome, $telefone, $empresa_id]);

            // Pega ID inserido
            $cliente_id = $pdo->lastInsertId();

            // Salva na sessão
            $_SESSION['cliente_id'] = $cliente_id;

            // Redireciona para o chat
            header("Location: mensagens.php?cliente_id=" . $cliente_id . "&empresa_id=" . $empresa_id);
            exit;
        } catch (PDOException $e) {
            $erro = "Erro ao salvar no banco: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
</head>

<body>

    <form action="" method="post">
        <div>
            Cadastre-se para enviar uma mensagem
        </div>

        <?php if (!empty($erro)) : ?>
            <p style="color:red;"><?= $erro ?></p>
        <?php endif; ?>

        <input type="text" name="nome" id="nome" placeholder="Informe seu nome">
        <br><br>

        <input type="text" name="telefone" id="telefone" placeholder="Informe seu telefone">
        <br><br>

        <input type="hidden" name="empresa_id" value="<?= $empresa_id ?>">

        <button type="submit">Cadastrar</button>
    </form>

</body>

</html>