<?php
session_start();
include "db.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$login = $_SESSION['login'];

// Busca os dados do usuário
$sql = "SELECT login, senha, nome FROM usuario WHERE login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Atualiza os dados se o formulário for enviado
if (isset($_POST['salvar'])) {
    $nome = trim($_POST['nome']);
    $senha = trim($_POST['senha']);


    $sqlUpdate = "UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE login = ?";
    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bind_param("ssss", $nome, $email, $senha, $login);
    $stmt->execute();

    $mensagem = "Dados atualizados com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="css/editar_perfil.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Editar Perfil</h1>
            <a href="home.php" class="voltar">← Voltar para Home</a>
        </header>

        <?php if (isset($mensagem)): ?>
            <p class="mensagem-sucesso"><?php echo $mensagem; ?></p>
        <?php endif; ?>

        <form method="POST" class="form-perfil">
            <label>Nome:</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>

            <label>Senha:</label>
            <input type="password" name="senha" value="<?php echo htmlspecialchars($usuario['senha']); ?>" required>

            <button type="submit" name="salvar" class="btn-salvar">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
