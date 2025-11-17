<?php
session_start();
include "db.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

// Busca todos os eventos no banco de dados
$sql = "SELECT * FROM eventos ORDER BY data ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Culturais</title>
    <link rel="stylesheet" href="css/eventos.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Eventos Culturais</h1>
            <a href="home.php" class="voltar">← Voltar para Home</a>
        </header>

        <section class="lista-eventos">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($evento = $result->fetch_assoc()): ?>
                    <div class="card-evento">
                        <h2><?php echo htmlspecialchars($evento['nome']); ?></h2>
                        <p><strong>Descrição:</strong> <?php echo htmlspecialchars($evento['descricao']); ?></p>
                        <p><strong>Local:</strong> <?php echo htmlspecialchars($evento['local']); ?></p>
                        <p><strong>Data:</strong> <?php echo date("d/m/Y", strtotime($evento['data'])); ?></p>
                        <p><strong>Hora:</strong> <?php echo date("H:i", strtotime($evento['hora'])); ?></p>
                        <p><strong>Capacidade:</strong> <?php echo $evento['capacidade']; ?> pessoas</p>

                        <?php if (!empty($evento['imagem'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($evento['imagem']); ?>" alt="Imagem do evento" class="imagem-evento">
                        <?php endif; ?>

                        <form action="reserva.php" method="POST">
                            <input type="hidden" name="id_evento" value="<?php echo $evento['id_evento']; ?>">
                            <button type="submit" class="btn-reservar">Reservar</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="mensagem">Nenhum evento disponível no momento.</p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
