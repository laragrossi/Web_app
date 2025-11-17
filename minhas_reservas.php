<?php
session_start();
include "db.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$login = $_SESSION['login'];

// Busca reservas do usuário logado
$sql = "SELECT r.id_reserva, e.nome AS nome_evento, e.local, e.data, e.hora, r.data_reserva, r.status_reserva
        FROM reservas r
        JOIN eventos e ON r.id_evento = e.id_evento
        WHERE login_usuario = ?
        ORDER BY data_reserva DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Reservas</title>
    <link rel="stylesheet" href="css/eventos.css">
</head>
<body>
    <div class="container">
        <header>
            <?php
            if (isset($_GET['msg'])) {
                if ($_GET['msg'] == 'sucesso') {
                    echo '<p class="mensagem-sucesso">Sua reserva foi feita com sucesso!</p>';
                } elseif ($_GET['msg'] == 'ja_reservado') {
                    echo '<p class="mensagem-aviso">Você já tem uma reserva ativa para este evento.</p>';
                }
            }
            ?>


            <h1>Minhas Reservas</h1>
            <a href="home.php" class="voltar">← Voltar para Home</a>
        </header>

        <section class="lista-eventos">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($reserva = $result->fetch_assoc()): ?>
                    <div class="card-evento">
                        <h2><?php echo htmlspecialchars($reserva['nome_evento']); ?></h2>
                        <p><strong>Local:</strong> <?php echo htmlspecialchars($reserva['local']); ?></p>
                        <p><strong>Data:</strong> <?php echo date("d/m/Y", strtotime($reserva['data'])); ?></p>
                        <p><strong>Hora:</strong> <?php echo date("H:i", strtotime($reserva['hora'])); ?></p>
                        <p><strong>Data da Reserva:</strong> <?php echo date("d/m/Y H:i", strtotime($reserva['data_reserva'])); ?></p>
                        <p><strong>Status:</strong> 
                            <?php echo ucfirst($reserva['status_reserva']); ?>
                        </p>

                        <?php if ($reserva['status_reserva'] == 'ativa'): ?>
                            <form action="cancelarreserva.php" method="POST">
                                <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
                                <button type="submit" class="btn-cancelar">Cancelar Reserva</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="mensagem">Você ainda não possui reservas.</p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>