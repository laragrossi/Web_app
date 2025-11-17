<?php
session_start();
include "db.php";

if (!isset($_SESSION['login']) || $_SESSION['tipo'] !== 'A') {
    header("Location: index.php");
    exit;
}

$sql = "SELECT * FROM eventos ORDER BY data ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Consultar Eventos</title>
    <link rel="stylesheet" href="../css/eventos_reservas.css" />
</head>
<body>
    <div class="container">
        <header>
            <h1>Eventos Cadastrados</h1>
            <a href="home.php" class="voltar">← Voltar</a>
        </header>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Local</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Capacidade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($evento = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($evento['nome']); ?></td>
                            <td><?php echo htmlspecialchars($evento['local']); ?></td>
                            <td><?php echo date("d/m/Y", strtotime($evento['data'])); ?></td>
                            <td><?php echo date("H:i", strtotime($evento['hora'])); ?></td>
                            <td><?php echo $evento['capacidade']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="mensagem">Nenhum evento cadastrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
