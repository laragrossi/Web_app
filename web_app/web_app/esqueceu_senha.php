<?php
include "db.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST['login']);
    $nova_senha = trim($_POST['nova_senha']);

    // Verifica se o login existe
    $sql = "SELECT * FROM USUARIO WHERE login=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $error = "Login não encontrado!";
    } else {
        // Atualiza a senha (hash)
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $update = "UPDATE USUARIO SET senha=?, quant_acesso=0, status='A' WHERE login=?";
        $stmtUpdate = $conn->prepare($update);
        $stmtUpdate->bind_param("ss", $senha_hash, $login);

        if ($stmtUpdate->execute()) {
            $success = "Senha redefinida com sucesso! Você já pode fazer login.";
        } else {
            $error = "Erro ao redefinir a senha: " . $stmtUpdate->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu a Senha</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="login-container">
    <h2>Redefinir Senha</h2>

    <?php if ($success != ""): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>
    <?php if ($error != ""): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <div class="input-group">
            <label for="login">E-mail (Login)</label>
            <input type="email" id="login" name="login" placeholder="Digite seu e-mail" required>
        </div>

        <div class="input-group">
            <label for="nova_senha">Senha atual</label>
            <input type="password" id="nova_senha" name="nova_senha" placeholder="Digite a nova senha" required>
        </div>

        <div class="input-group">
            <label for="nova_senha">Nova senha</label>
            <input type="password" id="nova_senha" name="nova_senha" placeholder="Digite a nova senha" required>
        </div>

        <div class="input-group">
            <label for="nova_senha">Confirmar senha</label>
            <input type="password" id="nova_senha" name="nova_senha" placeholder="Digite a nova senha" required>
        </div>


        <button type="submit">Redefinir Senha</button>
    </form>

    <p>Lembrou a senha?</p>
    <a href="index.php">
        <button type="button" class="btn-login">Voltar para Login</button>
    </a>
</div>
</body>
</html>
