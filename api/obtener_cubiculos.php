<?php
header("Content-Type: application/json");
require_once "../config/conexion.php";


if (!isset($_GET['personas']) || !isset($_GET['fecha'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Faltan parámetros: personas y fecha son obligatorios."
    ]);
    exit;
}

$personas = intval($_GET['personas']);
$fecha = $_GET['fecha'];

$horariosBase = [
    "08:00", "09:00", "10:00", "11:00",
    "12:00", "13:00", "14:00", "15:00",
    "16:00", "17:00"
];

$sql = "
    SELECT numCubiculo, capacidad
    FROM cubiculos
    WHERE capacidad >= ?
    ORDER BY numCubiculo ASC
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $personas);
$stmt->execute();
$res = $stmt->get_result();

$cubiculosCompatibles = [];
while ($row = $res->fetch_assoc()) {
    $cubiculosCompatibles[] = $row;
}

if (empty($cubiculosCompatibles)) {
    echo json_encode([
        "status" => "ok",
        "cubiculos" => [],
        "message" => "No hay cubículos con esa capacidad."
    ]);
    exit;
}


$cubiculosFinales = [];

foreach ($cubiculosCompatibles as $cub) {

    $sqlH = "
        SELECT horaInicio
        FROM reservas
        WHERE numCubiculo = ?
        AND fecha = ?
    ";

    $stmtH = $mysqli->prepare($sqlH);
    $stmtH->bind_param("is", $cub['numCubiculo'], $fecha);
    $stmtH->execute();
    $resH = $stmtH->get_result();

    $ocupadas = [];
    while ($r = $resH->fetch_assoc()) {
        $ocupadas[] = substr($r['horaInicio'], 0, 5);
    }

    $disponibles = array_values(array_diff($horariosBase, $ocupadas));

    if (count($disponibles) == 0) {
        continue;
    }

    $cubiculosFinales[] = [
        "numCubiculo" => $cub['numCubiculo'],
        "capacidad" => $cub['capacidad'],
        "horarios_disponibles" => $disponibles
    ];
}

echo json_encode([
    "status" => "ok",
    "cubiculos" => $cubiculosFinales,
    "total" => count($cubiculosFinales)
]);
?>
