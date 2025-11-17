<?php
session_start();
include "db.php";

if (!isset($_SESSION['login']) || $_SESSION['tipo'] !== 'A') {
    header("Location: index.php");
    exit;
}

// Contar reservas ativas
$sqlTotalReservas = "SELECT COUNT(*) AS total FROM reservas WHERE status_reserva = 'ativa'";
$resultTotal = $conn->query($sqlTotalReservas);
$totalReservas = $resultTotal->fetch_assoc()['total'];

// Consultar reservas agrupadas por evento
$sqlReservasEventos = "SELECT e.nome, COUNT(r.id_reserva) AS total_reservas
                       FROM eventos e
                       LEFT JOIN reservas r ON e.id_evento = r.id_evento AND r.status_reserva = 'ativa'
                       GROUP BY e.id_evento, e.nome";
$resultReservasEventos = $conn->query($sqlReservasEventos);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Reservas Feitas</title>
    <link rel="stylesheet" href="../css/eventos_reservas.css" />
</head>
<body>
    <div class="container">
        <header>
            <h1>Reservas Feitas</h1>
            <a href="home.php" class="voltar">← Voltar</a>
        </header>

        <p class="total-reservas"><strong>Total de reservas ativas:</strong> <?php echo $totalReservas; ?></p>

        <h2>Reservas por Evento</h2>

        <?php if ($resultReservasEventos && $resultReservasEventos->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Quantidade de Reservas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultReservasEventos->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nome']); ?></td>
                            <td><?php echo $row['total_reservas']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="mensagem">Nenhuma reserva encontrada.</p>
        <?php endif; ?>
    </div>
</body>
</html>