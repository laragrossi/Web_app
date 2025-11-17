<?php
session_start();

// Se o usuário não estiver logado, redireciona
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$nomeUsuario = $_SESSION['nome'];
$tipoUsuario = $_SESSION['tipo']; // 'A' para admin, 'C' para comum
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Sistema de Reservas</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <div class="home-container">
        <h1>Bem-vindo(a), <?php echo htmlspecialchars($nomeUsuario); ?>!</h1>
        <p class="subtitulo">Escolha uma das opções abaixo para continuar:</p>

        <div class="botoes">
            <a href="eventos.php" class="btn">Eventos Culturais</a>
            <a href="minhas_reservas.php" class="btn">Minhas Reservas</a>
            <a href="editar_perfil.php" class="btn">Editar Perfil</a>

            <?php if ($tipoUsuario === 'A'): ?>
                <a href="criar_evento.php" class="btn admin">Criar Evento</a>
            <?php endif; ?>

            <a href="logout.php" class="btn sair">Sair</a>
        </div>
    </div>
</body>
</html>
