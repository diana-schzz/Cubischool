<?php
session_start();

if (!isset($_SESSION['idIest']) || $_SESSION['tipoUsuario'] != 1) {
    header("Location: ../login_user.php");
    exit();
}

require_once "../../config/conexion.php";

if (!isset($_POST['idReserva'])) {
    echo "<script>alert('Reserva inválida'); window.location.href='mis_reservas.php';</script>";
    exit();
}

$idReserva = intval($_POST['idReserva']);
$idIest = $_SESSION['idIest'];

$sqlCheck = "
    SELECT estado 
    FROM reservas 
    WHERE idReserva = ? AND idIest = ? 
";
$stmt = $mysqli->prepare($sqlCheck);
$stmt->bind_param("ii", $idReserva, $idIest);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "<script>alert('No tienes permiso para cancelar esta reserva'); window.location.href='mis_reservas.php';</script>";
    exit();
}

$row = $res->fetch_assoc();
if ($row['estado'] == 3) {
    echo "<script>alert('Esta reserva ya está cancelada'); window.location.href='mis_reservas.php';</script>";
    exit();
}

$sqlUpdate = "
    UPDATE reservas 
    SET estado = 3 
    WHERE idReserva = ?
";
$stmt2 = $mysqli->prepare($sqlUpdate);
$stmt2->bind_param("i", $idReserva);
$stmt2->execute();

echo "<script>
    alert('La reserva ha sido cancelada correctamente.');
    window.location.href='mis_reservas.php';
</script>";
