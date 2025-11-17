<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);

    // Busca usuário
    $sql = "SELECT * FROM USUARIO WHERE login=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $error = "Login não encontrado.";
    } else {
        // Verifica status
        if ($user['status'] === 'B') {
            $error = "Conta bloqueada. Contate o administrador.";
        } else {
            // Verifica senha
            if (password_verify($senha, $user['senha'])) {
                // Zera contador de tentativas e incrementa quant_acesso
                $conn->query("UPDATE USUARIO SET quant_acesso = 0+1 WHERE login='{$login}'");

                $_SESSION['login'] = $user['login'];
                $_SESSION['nome'] = $user['nome'];
                $_SESSION['tipo'] = $user['tipo'];

                header("Location: home.php");
                exit;
            } else {
                // Incrementa tentativas
                $tentativas = $user['quant_acesso'] + 1;

                if ($tentativas >= 3) {
                    $conn->query("UPDATE USUARIO SET status='B', quant_acesso=$tentativas WHERE login='{$login}'");
                    $error = "Senha incorreta. Conta bloqueada após 3 tentativas.";
                } else {
                    $conn->query("UPDATE USUARIO SET quant_acesso=$tentativas WHERE login='{$login}'");
                    $error = "Senha incorreta. Tentativa $tentativas de 3.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form id="loginForm" method="post" action="">
            <div class="input-group">
                <label for="username">Usuário</label>
                <input type="text" id="username" name="login" placeholder="Digite seu usuário" required>
            </div>

            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="senha" placeholder="Digite sua senha" required>
            </div>

            <a href="esqueceu_senha.php">Esqueceu a senha?</a>
            <br><br>
            <button type="submit">Entrar</button>
        </form>

        <!-- Botão de cadastro -->
        <p>Ainda não tem conta?</p>
        <a href="cadastro.php">
            <button type="button" class="btn-cadastro">Cadastrar</button>
        </a>

        <?php if ($error != ""): ?>
            <p id="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>

    <script src="javascript/script.js"></script>
</body>
</html>
