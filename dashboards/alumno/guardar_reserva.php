<?php
session_start();

if (!isset($_SESSION['idIest'])) {
    header("Location: ../login_user.php");
    exit();
}

require_once "../../config/conexion.php";

$idIest        = $_SESSION['idIest'];
$numCubiculo   = $_POST['id_cubiculo'];
$fecha         = $_POST['fecha'];
$horaInicio    = $_POST['hora'];
$horaFin       = date("H:i", strtotime($horaInicio . " +1 hour")); 
$nombre        = $_POST['nombre_reserva'];
$material      = isset($_POST['plumones']) ? 1 : 0;
$estado        = 1; // Activa

// Validación básica:
if (empty($numCubiculo) || empty($fecha) || empty($horaInicio)) {
    echo "<script>alert('Faltan datos para guardar la reserva'); window.history.back();</script>";
    exit;
}

// INSERT CORRECTO SEGÚN TU TABLA
$sql = "
    INSERT INTO reservas (idIest, numCubiculo, horaInicio, horaFin, fecha, estado, nombre, material)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iisssisi", 
    $idIest,
    $numCubiculo,
    $horaInicio,
    $horaFin,
    $fecha,
    $estado,
    $nombre,
    $material
);

if ($stmt->execute()) {
    echo "<script>alert('Reserva realizada con éxito'); window.location.href='mis_reservas.php';</script>";
} else {
    echo "Error al guardar reserva: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
