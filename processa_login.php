<?php
session_start();

// Inclui o arquivo de conexão com o banco de dados
require './database/conexao.php';

// Obtém os dados do formulário
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Validações básicas
if (empty($email) || empty($senha)) {
    header('Location: login.html?mensagem=Preencha todos os campos&tipo=erro');
    exit;
}

try {
    // BUSCA O USUÁRIO NO BANCO DE DADOS
    $sql = "SELECT id, nome, email, senha FROM usuarios WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Login bem-sucedido
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        
        // Redireciona para o chat
        header('Location: chatt.php');
        exit;
    } else {
        // Login falhou
        header('Location: login.html?mensagem=E-mail ou senha incorretos&tipo=erro');
        exit;
    }

} catch (PDOException $e) {
    header('Location: login.html?mensagem=Erro no servidor&tipo=erro');
    exit;
}
?>