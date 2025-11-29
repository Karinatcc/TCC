<?php
session_start();

// Simulação de validação (substitua pela sua lógica real)
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Exemplo de validação - substitua pela sua lógica de banco de dados
$usuarios_validos = [
    'admin@exemplo.com' => '123456',
    'usuario@exemplo.com' => 'senha123'
];

if (isset($usuarios_validos[$email]) && $usuarios_validos[$email] === $senha) {
    // Login bem-sucedido
    $_SESSION['usuario'] = $email;
    header('Location: login.html?mensagem=Login realizado com sucesso!&tipo=sucesso');
} else {
    // Login falhou
    header('Location: login.html?mensagem=E-mail ou senha incorretos!&tipo=erro');
}
exit;
?>