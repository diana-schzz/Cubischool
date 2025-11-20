<?php
session_start();

if (!isset($_SESSION['nombre']) || $_SESSION['tipoUsuario'] != 1) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

require_once "../../config/conexion.php";

$personas = isset($_GET['personas']) ? (int)$_GET['personas'] : 0;
$fecha    = $_GET['fecha'] ?? '';

if ($personas <= 0 || empty($fecha)) {
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}


$sql = "
    SELECT id, nombre
    FROM cubiculos
    WHERE 
      (
        (id BETWEEN 1 AND 4 AND ? <= 6)
        OR
        (id BETWEEN 5 AND 7 AND ? <= 3)
        OR
        (id BETWEEN 8 AND 12 AND ? <= 10)
      )
    ORDER BY id
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('iii', $personas, $personas, $personas);
$stmt->execute();
$result = $stmt->get_result();

$cubiculos = [];
while ($row = $result->fetch_assoc()) {
    $cubiculos[] = $row;
}

echo json_encode(['cubiculos' => $cubiculos]);

