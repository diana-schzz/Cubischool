<?php
session_start();

if (!isset($_SESSION['nombre']) || $_SESSION['tipoUsuario'] != 3) {
    header("Location: ../login_user.php");
    exit();
}

require_once "../../config/conexion.php";

if (!isset($_GET['id'])) {
    header("Location: usuarios.php");
    exit();
}

$idIest = $_GET['id'];

$stmt = $mysqli->prepare("DELETE FROM usuario WHERE idIest = ?");
$stmt->bind_param("s", $idIest);
$stmt->execute();
$stmt->close();

header("Location: usuarios.php?deleted=1");
exit();
