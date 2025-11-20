<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['idIest'])) {
    echo json_encode([
        "status" => "error",
        "message" => "No autorizado"
    ]);
    exit;
}

require_once "../config/conexion.php";

if (empty($_GET['numCubiculo']) || empty($_GET['fecha'])) {
    echo json_encode([
        "status"  => "error",
        "message" => "Faltan parámetros: numCubiculo y fecha son obligatorios."
    ]);
    exit;
}

$numCubiculo = (int) $_GET['numCubiculo'];
$fecha       = $_GET['fecha'];

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
    echo json_encode([
        "status"  => "error",
        "message" => "Formato de fecha inválido. Usa YYYY-MM-DD."
    ]);
    exit;
}

$horarios = [
    "08:00", "09:00", "10:00", "11:00",
    "12:00", "13:00", "14:00", "15:00",
    "16:00", "17:00"
];

// 3. Obtener horarios ya reservados para ese cubículo y fecha
$sql = "
    SELECT horaInicio
    FROM reservas
    WHERE numCubiculo = ?
      AND fecha = ?
      AND estado = 1   -- opcional: solo reservas activas
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("is", $numCubiculo, $fecha);
$stmt->execute();
$res = $stmt->get_result();

$ocupados = [];
while ($row = $res->fetch_assoc()) {
    $ocupados[] = substr($row["horaInicio"], 0, 5); // HH:MM
}
$stmt->close();

// 4. Calcular horarios disponibles
$disponibles = array_values(array_diff($horarios, $ocupados));

// 5. Respuesta JSON
echo json_encode([
    "status"             => "ok",
    "cubiculo"           => $numCubiculo,
    "fecha"              => $fecha,
    "horarios_disponibles" => $disponibles,
    "horarios_ocupados"  => $ocupados,
    "total_disponibles"  => count($disponibles)
]);
