<?php
session_start();
require 'db.php';

if ($_SESSION['user']['tipo'] !== 'A') {
    header("Location: principal.php");
    exit;
}

$stmt = $pdo->query("SELECT login, nome, tipo, status FROM usuarios ORDER BY nome");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Consultar Usuários</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Usuários Cadastrados</h2>

        <table border="1" cellpadding="8">
            <tr>
                <th>Login</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Status</th>
            </tr>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['login']) ?></td>
                    <td><?= htmlspecialchars($u['nome']) ?></td>
                    <td><?= $u['tipo'] === 'A' ? 'Admin' : 'Normal' ?></td>
                    <td><?= $u['status'] === 'A' ? 'Ativo' : 'Bloqueado' ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p><a href="principal.php">Voltar</a></p>
    </div>
</body>
</html>
