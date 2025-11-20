<?php
// Activar reportes de errores de MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Definir constantes para la conexión
define('DB_HOST', 'localhost');
define('DB_NAME', 'cubischool');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    // Crear conexión
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Establecer el charset UTF-8
    $mysqli->set_charset("utf8mb4");

} catch (Exception $e) {
    // Manejo elegante del error
    error_log("Error de conexión: " . $e->getMessage());
    exit("Error al conectar a la base de datos. Intenta más tarde.");
}
?>

