<?php
  $usuario = $_POST['usuario'] ?? '';
  $senha = $_POST['senha'] ?? '';

  // Simulação (usuário fixo: admin / senha: 1234)
  if ($usuario === 'admin' && $senha === '1234') {
      $mensagem = "Bem-vindo, $usuario!";
  } else {
      header("Location: index.html");
      exit;
  }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Home</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="login-container">
    <h2><?php echo $mensagem; ?></h2>
    <p>Você está logado no sistema.</p>
    <a href="index.html" class="link">Sair</a>
  </div>
</body>
</html>
