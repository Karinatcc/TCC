<?php
session_start();
require 'database/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Usuário não está logado");
}

$usuarioId = $_SESSION['usuario_id'];
$link = "localhost/tcc/cadastro-cliente.php?empresa_id=$usuarioId";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Link de Cadastro</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #5f2c82, #49a09d);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 14px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .card h2 {
            margin-bottom: 10px;
            color: #333;
        }

        .card p {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .link-box {
            display: flex;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .link-box input {
            flex: 1;
            border: none;
            padding: 12px;
            font-size: 14px;
            outline: none;
        }

        .link-box button {
            background: #49a09d;
            border: none;
            color: white;
            padding: 0 18px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.2s;
        }

        .link-box button:hover {
            background: #3b8b88;
        }

        .abrir-btn {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            background: #5f2c82;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.2s;
        }

        .abrir-btn:hover {
            background: #4a2266;
        }

        .msg {
            font-size: 13px;
            margin-top: 12px;
            color: #28a745;
            display: none;
        }
    </style>
</head>

<body>

    <div class="card">
        <h2>Link de Cadastro</h2>
        <p>Envie este link para o seu cliente se cadastrar</p>

        <div class="link-box">
            <input type="text" id="linkCadastro" value="<?= $link ?>" readonly>
            <button onclick="copiarLink()">Copiar</button>
        </div>

        <a class="abrir-btn" href="<?= $link ?>" target="_blank">Abrir Página</a>

        <div class="msg" id="msgCopiado">✅ Link copiado com sucesso!</div>
    </div>

    <script>
        function copiarLink() {
            const input = document.getElementById('linkCadastro');
            input.select();
            input.setSelectionRange(0, 99999);
            document.execCommand("copy");

            const msg = document.getElementById('msgCopiado');
            msg.style.display = 'block';

            setTimeout(() => {
                msg.style.display = 'none';
            }, 2000);
        }
    </script>

</body>

</html>