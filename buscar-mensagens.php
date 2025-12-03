<?php
require './database/conexao.php';

$cliente_id = $_GET['cliente_id'] ?? '';
$empresa_id = $_GET['empresa_id'] ?? '';

$query = "SELECT * FROM mensagens 
          WHERE cliente_id = ? AND empresa_id = ?
          ORDER BY criado_em ASC";

$stmt = $pdo->prepare($query);
$stmt->execute([$cliente_id, $empresa_id]);
$mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($mensagens);
