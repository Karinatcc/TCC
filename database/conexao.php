<?php
// conexão.php
// CONEXÃO SQLITE REUTILIUZÁVEL PARA TODA A APLICAÇÃO

// CAMINHO PARA O ARQUIVO DO BANCO DE DADOS (AJUSTE CONFORME SUA ESTRUTURA)
$db_file = __DIR__ . '/db.sqlite';

try {
    // CRIA (OU ABRE) O BANCO DE DADOS SQLITE
    $pdo = new PDO('sqlite:' . $db_file);

    // CONFIGURAÇÕES RECOMENDADAS
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // EXIBE ERROS
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // RETORNA ARRAYS ASSOCIATIVOS
    $pdo->exec('PRAGMA foreign_keys = ON'); // ATIVA CHAVES ESTRANGEIRAS

} catch (PDOException $e) {
    // EM CASO DE ERRO, MOSTRO MENSAGEM E ENCERRO
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
