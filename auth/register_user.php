<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $correoInstitucional = trim($_POST['correoInstitucional']);
    $nombre             = trim($_POST['nombre']);
    $idIest             = trim($_POST['idIest']);
    $contrasena         = trim($_POST['contrasena']);

    if ($correoInstitucional === "" || $nombre === "" || $idIest === "" || $contrasena === "") {
        die("Todos los campos son obligatorios.");
    }

    $contrasenaHash = password_hash($contrasena, PASSWORD_BCRYPT);

    $tipoUsuario = 1;

    $queryCheck = "SELECT idIest FROM usuario WHERE idIest = ? OR correoInstitucional = ?";
    $stmtCheck = $mysqli->prepare($queryCheck);
    $stmtCheck->bind_param("is", $idIest, $correoInstitucional);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        $stmtCheck->close();
        die("<script>alert('Ya existe un usuario con ese ID IEST o correo institucional.'); window.history.back();</script>");
    }
    $stmtCheck->close();

    $query = "INSERT INTO usuario (correoInstitucional, nombre, idIest, contrasena, tipoUsuario)
              VALUES (?, ?, ?, ?, ?)";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssisi", $correoInstitucional, $nombre, $idIest, $contrasenaHash, $tipoUsuario);

    if ($stmt->execute()) {
        $stmt->close();
        echo "<script>alert('Registro exitoso. Ahora puedes iniciar sesión.'); window.location.href='../index.php';</script>";
        exit();
    } else {
        echo "Error al registrar: " . $stmt->error;
    }

    $stmt->close();
}

$mysqli->close();
?>
