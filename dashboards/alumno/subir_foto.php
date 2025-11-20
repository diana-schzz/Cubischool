<?php
session_start();

if (!isset($_SESSION['idIest'])) {
    die("Acceso denegado");
}

require_once "../../config/conexion.php";

$idIest = $_SESSION['idIest'];

if (!empty($_FILES['foto']['name'])) {

    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $permitidas = ['jpg', 'jpeg', 'png'];

    if (!in_array(strtolower($ext), $permitidas)) {
        die("Formato no permitido");
    }

    // Nombre único
    $nuevoNombre = "perfil_" . $idIest . "_" . time() . "." . $ext;
    $rutaDestino = "../../uploads/perfiles/" . $nuevoNombre;

    // Guardar archivo
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {

        // Guardar en BD
        $sql = "UPDATE usuario SET fotoPerfil = ? WHERE idIest = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $nuevoNombre, $idIest);
        $stmt->execute();

        header("Location: perfil.php?subido=1");
        exit;

    } else {
        die("Error al subir la imagen.");
    }
}

header("Location: perfil.php?error=1");
