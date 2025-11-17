<?php
session_start();
include "db.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reserva = $_POST['id_reserva'];
    $login = $_SESSION['login'];

    // Atualiza o status para 'cancelada'
    $sql = "UPDATE reservas SET status_reserva = 'cancelada' WHERE id_reserva = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Guarda a mensagem na sessão
        $_SESSION['mensagem'] = "Reserva cancelada com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao cancelar a reserva.";
    }

    // Redireciona de volta para a página de reservas
    header("Location: minhas_reservas.php");
    exit;
    header("Location: minhas_reservas.php");
    exit;
}
