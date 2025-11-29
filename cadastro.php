<?php 

if ($_POST) {
  // Inclui o arquivo de conexão com o banco de dados
  require './database/conexao.php';

  // Obtém os dados do formulário
  $nome = $_POST['nome'] ?? '';
  $email = $_POST['email'] ?? '';
  $senha = $_POST['senha'] ?? '';
  $telefone = $_POST['telefone'] ?? '';

  // Limpar e formatar o telefone
  $telefone = preg_replace('/[^0-9]/', '', $telefone);

  // Validações básicas
  if(empty($nome) || empty($email) || empty($senha) || empty($telefone)) {
    $mensagem = "Preencha todos os campos obrigatórios";
    $tipo = "erro";
  } 
  // Validação do telefone
  elseif (strlen($telefone) < 10 || strlen($telefone) > 11) {
    $mensagem = "Telefone inválido. Digite DDD + número (10 ou 11 dígitos)";
    $tipo = "erro";
  } else {
    try {
      // VERIFICAR SE TELEFONE JÁ EXISTE
      $sql_verifica_telefone = "SELECT id FROM usuarios WHERE telefone = ?";
      $stmt_verifica = $pdo->prepare($sql_verifica_telefone);
      $stmt_verifica->execute([$telefone]);
      
      if ($stmt_verifica->fetch()) {
        $mensagem = "Este número de telefone já está cadastrado";
        $tipo = "erro";
      } else {
        // VERIFICAR SE EMAIL JÁ EXISTE
        $sql_verifica_email = "SELECT id FROM usuarios WHERE email = ?";
        $stmt_verifica_email = $pdo->prepare($sql_verifica_email);
        $stmt_verifica_email->execute([$email]);
        
        if ($stmt_verifica_email->fetch()) {
          $mensagem = "Este e-mail já está cadastrado";
          $tipo = "erro";
        } else {
          // INSERIR NO BANCO
          $sql = "INSERT INTO usuarios (nome, email, senha, telefone) VALUES (?, ?, ?, ?)";
          $stmt = $pdo->prepare($sql);

          // Criptografa a senha
          $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

          // Executa a query
          $stmt->execute([$nome, $email, $senhaHash, $telefone]);

          $mensagem = "Cadastro realizado com sucesso!";
          $tipo = "sucesso";
          
          // Limpar campos
          $nome = $email = $telefone = '';
        }
      }

    } catch (PDOException $th) {
      $mensagem = "Erro no cadastro: " . $th->getMessage();
      $tipo = "erro";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro</title>
  <link rel="stylesheet" href="css/cadastro.css">
  <link rel="stylesheet" href="css/mensagens-de-feedback.css">
</head>
<body>
  <div class="form-container">
    <h2>Cadastre-se</h2>
    
    <!-- Área para exibir mensagens de feedback -->
    <?php if (isset($mensagem)): ?>
      <div class="mensagem <?php echo $tipo; ?>">
        <?php echo htmlspecialchars($mensagem); ?>
      </div>
    <?php endif; ?>

    <!-- Formulário de cadastro -->
    <form method='post' action="cadastro.php">
      <label for="nome">Nome completo</label>
      <input type="text" id="nome" name="nome" placeholder="Maria silva" 
             value="<?php echo isset($nome) ? htmlspecialchars($nome) : ''; ?>" required>

      <label for="email">E-mail comercial</label>
      <input type="email" id="email" name="email" placeholder="nome@gmail.com" 
             value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>

      <label for="senha">Senha</label>
      <input type="password" id="senha" name="senha" placeholder="Mínimo 5 caracteres" required minlength="5">

      <label for="telefone">Número de telefone celular</label>
      <input type="tel" id="telefone" name="telefone" placeholder="(12) 88888-7777" 
             value="<?php echo isset($telefone) ? htmlspecialchars($telefone) : ''; ?>" required>

      <button type="submit" class="btn-primary">Inscrever-se</button>
    </form>
  </div>
</body>
</html>