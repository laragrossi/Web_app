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
$sql = "SELECT login, nome FROM usuario WHERE login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Atualiza os dados se o formulário for enviado
if (isset($_POST['salvar'])) {
    $nome = trim($_POST['nome']);
    $senha = trim($_POST['senha']);

    $sqlUpdate = "UPDATE usuario SET nome = ?, senha = ? WHERE login = ?";
    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bind_param("sss", $nome, $senha, $login);
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
    <link rel="stylesheet" href="../css/perfil.css">
    <style>
        .senha-container {
            position: relative;
        }
        .toggle-senha {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #333;
        }
    </style>
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
            <div class="senha-container">
                <input type="password" id="senha" name="senha" placeholder="Digite a nova senha" required>
            </div>

            <button type="submit" name="salvar" class="btn-salvar">Salvar Alterações</button>
        </form>
    </div>

</body>
</html>

