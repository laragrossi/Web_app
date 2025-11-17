<?php
session_start();
include "db.php";

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_evento = $_POST['id_evento'];
    $login_usuario = $_SESSION['login'];

    // Verifica se já existe reserva ativa para esse evento e usuário
    $sqlCheck = "SELECT id_reserva FROM reservas WHERE login_usuario = ? AND id_evento = ? AND status_reserva = 'ativa'";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("si", $login_usuario, $id_evento);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        // Já tem reserva ativa, redireciona com aviso
        header("Location: minhas_reservas.php?msg=ja_reservado");
        exit;
    } else {
        // Insere nova reserva
        $sqlReserva = "INSERT INTO reservas (login_usuario, id_evento, data_reserva, status_reserva) VALUES (?, ?, NOW(), 'ativa')";
        $stmtReserva = $conn->prepare($sqlReserva);
        $stmtReserva->bind_param("si", $login_usuario, $id_evento);

        if ($stmtReserva->execute()) {
            header("Location: minhas_reservas.php?msg=sucesso");
            exit;
        } else {
            echo "Erro ao fazer a reserva.";
        }
    }
} else {
    header("Location: eventos.php");
    exit;
}