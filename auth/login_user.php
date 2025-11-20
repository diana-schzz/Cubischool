<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../config/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $idIest = trim($_POST["idIest"]);
    $password = trim($_POST["password"]);

    if ($idIest === "" || $password === "") {
        echo "<script>alert('Todos los campos son obligatorios.'); window.history.back();</script>";
        exit();
    }

    $query = "SELECT idIest, nombre, correoInstitucional, contrasena, tipoUsuario 
              FROM usuario 
              WHERE idIest = ?";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $idIest);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {

        $stmt->bind_result($dbId, $dbNombre, $dbCorreo, $dbContrasenaHash, $dbTipoUsuario);
        $stmt->fetch();

        if (password_verify($password, $dbContrasenaHash)) {

            $_SESSION["idIest"] = $dbId;
            $_SESSION["nombre"] = $dbNombre;
            $_SESSION["correoInstitucional"] = $dbCorreo;
            $_SESSION["tipoUsuario"] = $dbTipoUsuario;

            switch ($dbTipoUsuario) {
                case 1:
                    header("Location: ../dashboards/alumno/inicio.php");
                    break;

                case 2:
                    header("Location: ../dashboards/secretaria/inicio.php");
                    break;

                case 3:
                    header("Location: ../dashboards/admin/inicio.php");
                    break;

                default:
                    echo "<script>alert('Tipo de usuario no reconocido.');</script>";
                    break;
            }

            exit();

        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.history.back();</script>";
            exit();
        }

    } else {
        echo "<script>alert('El ID IEST no existe.'); window.history.back();</script>";
        exit();
    }

    $stmt->close();
}

$mysqli->close();
?>
