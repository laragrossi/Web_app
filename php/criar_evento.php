<?php
session_start();
include "db.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

// Verifica se o tipo do usuário é administrador
if ($_SESSION['tipo'] !== 'A') {  // supondo que 'A' = administrador e 'C' = comum
    echo "<script>
            alert('Apenas administradores podem criar eventos!');
            window.location.href = 'home.php';
          </script>";
    exit;
}

// Quando o formulário for enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $local = $_POST['local'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $capacidade = $_POST['capacidade'];
    $imagem = $_FILES['imagem']['name'];
    $status = "Ativo";

    // Caminho da imagem
    $target = "uploads/" . basename($imagem);

    $sql = "INSERT INTO eventos (nome, descricao, local, data, hora, capacidade, imagem) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssis", $nome, $descricao, $local, $data, $hora, $capacidade, $imagem);

    if ($stmt->execute()) {
        move_uploaded_file($_FILES['imagem']['tmp_name'], $target);
        $mensagem = "✅ Evento cadastrado com sucesso!";
    } else {
        $erro = "❌ Erro ao cadastrar evento: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Evento</title>
    <link rel="stylesheet" href="css/criar_evento.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎭 Criar Evento Cultural</h1>
            <a href="eventos.php" class="voltar">← Voltar</a>
        </header>

        <?php if (isset($mensagem)): ?>
            <p class="sucesso"><?php echo $mensagem; ?></p>
        <?php elseif (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="form-evento">
            <label>Nome do Evento:</label>
            <input type="text" name="nome" required>

            <label>Descrição:</label>
            <textarea name="descricao" required></textarea>

            <label>Local:</label>
            <input type="text" name="local" required>

            <label>Data:</label>
            <input type="date" name="data" required>

            <label>Hora:</label>
            <input type="time" name="hora" required>

            <label>Capacidade:</label>
            <input type="number" name="capacidade" required>

            <label>Imagem do Evento:</label>
            <input type="file" name="imagem" accept="image/*">

            <button type="submit" class="btn-criar">Criar Evento</button>
        </form>
    </div>
</body>
</html>
