<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Gratuito</title>
    <link rel="stylesheet" href="css/gratuito.css">
    <link rel="stylesheet" href="css/mensagens-de-feedback.css">
</head>
<body>
     <div class="form-gratuito">
        <h2>Cadastre-se</h2>
        
        <!-- Área para mensagens de feedback -->
        <?php if (isset($_GET['mensagem'])): ?>
            <div class="mensagem <?php echo $_GET['tipo'] ?? 'info'; ?>">
                <?php echo htmlspecialchars($_GET['mensagem']); ?>
            </div>
        <?php endif; ?>

        <!-- Formulário apontando para cadastro.php -->
        <form method="POST" action="cadastro.php">
            <label for="nome">Nome completo</label>
            <input type="text" id="nome" name="nome" placeholder="Maria silva" required>

            <label for="email">E-mail comercial</label>
            <input type="email" id="email" name="email" placeholder="exemplo@gmail.com" required>

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Defina sua senha" required minlength="5">

            <label for="telefone">Número de telefone celular</label>
            <input type="tel" id="telefone" name="telefone" placeholder="(11) 96123-4567" required>
            
            <button type="submit" class="btn-primary">Cadastrar</button>
        </form>
    </div>
    
    <script src="js/teste.js"></script>
</body>
</html>