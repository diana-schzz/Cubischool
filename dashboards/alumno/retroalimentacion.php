<?php
session_start();

if (!isset($_SESSION['idIest'])) {
    header("Location: login_user.php");
    exit();
}

require_once '../../config/conexion.php';

$correo = $_POST['correoInstitucional'];
$nombre = $_POST['nombre'];
$idIest = $_POST['idIest'];
$mensaje = $_POST['mensaje'];

$stmt = $mysqli->prepare("
    INSERT INTO retroalimentacion (correoInstitucional, nombre, idIest, mensaje)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("ssis", $correo, $nombre, $idIest, $mensaje);

if ($stmt->execute()) {
    echo "<script>alert('Mensaje enviado exitosamente'); window.location.href='inicio.php';</script>";
} else {
    echo "Error al guardar: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
