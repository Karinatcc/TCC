<?php
session_start();
require './database/conexao.php';

// === MEDIDA DE SEGURANÇA SIMPLES: Rate Limiting ===
$ip = $_SERVER['REMOTE_ADDR'];
$rate_key = 'recuperar_' . $ip;

if (!isset($_SESSION[$rate_key])) {
    $_SESSION[$rate_key] = 1;
} else {
    $_SESSION[$rate_key]++;
}

if ($_SESSION[$rate_key] > 5) {
    header('Location: html/recuperar-senha.html?mensagem=Muitas tentativas. Tente novamente mais tarde.&tipo=erro');
    exit;
}

// === MEDIDA DE SEGURANÇA: Validar email ===
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: html/recuperar-senha.html?mensagem=E-mail inválido&tipo=erro');
    exit;
}

// === SEU CÓDIGO ORIGINAL ===
$nova_senha = $_POST['nova_senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';

// Validações básicas
if (empty($email) || empty($nova_senha) || empty($confirmar_senha)) {
    header('Location: html/recuperar-senha.html?mensagem=Preencha todos os campos&tipo=erro');
    exit;
}

// Verificar se as senhas coincidem
if ($nova_senha !== $confirmar_senha) {
    header('Location: html/recuperar-senha.html?mensagem=As senhas não coincidem&tipo=erro');
    exit;
}

// Verificar tamanho mínimo da senha
if (strlen($nova_senha) < 5) {
    header('Location: html/recuperar-senha.html?mensagem=A senha deve ter no mínimo 5 caracteres&tipo=erro');
    exit;
}

try {
    // Verificar se o email existe no banco
    $sql_verifica_email = "SELECT id, nome FROM usuarios WHERE email = ?";
    $stmt_verifica = $pdo->prepare($sql_verifica_email);
    $stmt_verifica->execute([$email]);
    $usuario = $stmt_verifica->fetch();

    if (!$usuario) {
        header('Location: html/recuperar-senha.html?mensagem=E-mail não encontrado em nossa base de dados&tipo=erro');
        exit;
    }

    // Atualizar a senha no banco
    $sql_atualizar = "UPDATE usuarios SET senha = ? WHERE email = ?";
    $stmt_atualizar = $pdo->prepare($sql_atualizar);
    
    // Criptografar a nova senha
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    $resultado = $stmt_atualizar->execute([$senha_hash, $email]);

    if ($resultado) {
        // === MEDIDA DE SEGURANÇA: Limpar rate limit em caso de sucesso ===
        unset($_SESSION[$rate_key]);
        
        // REDIRECIONAMENTO DIRETO - SEM PÁGINA INTERMEDIÁRIA
        header('Location: login.html?mensagem=Senha alterada com sucesso! Faça login com sua nova senha&tipo=sucesso');
        exit;
    } else {
        header('Location: html/recuperar-senha.html?mensagem=Erro ao alterar senha. Tente novamente&tipo=erro');
        exit;
    }

} catch (PDOException $e) {
    header('Location: html/recuperar-senha.html?mensagem=Erro no servidor. Tente novamente&tipo=erro');
    exit;
}
?>