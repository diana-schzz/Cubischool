<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: ../login_user.php");
    exit;
}

require_once "../../config/conexion.php";

$usuariosSQL = $mysqli->query("SELECT COUNT(*) AS total FROM usuario");
$usuarios = $usuariosSQL->fetch_assoc()['total'];

$hoy = date('Y-m-d');
$reservasSQL = $mysqli->query("SELECT COUNT(*) AS total FROM reservas WHERE fecha = '$hoy'");
$reservasHoy = $reservasSQL->fetch_assoc()['total'];

$cubiculosSQL = $mysqli->query("SELECT COUNT(*) AS total FROM cubiculos WHERE estatus = 0");
$cubiculosDisponibles = $cubiculosSQL->fetch_assoc()['total'];

$avisosActivosSQL = $mysqli->query("SELECT COUNT(*) AS total FROM avisos");
$avisosActivos = $avisosActivosSQL->fetch_assoc()['total'];

$avisosRecientesSQL = $mysqli->query("SELECT titulo, fecha FROM avisos ORDER BY fecha DESC LIMIT 5");

?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>CUBISCHOOL - Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #f7f7f7;
            font-family: "Poppins", sans-serif;
        }

        .main-content {
            flex: 1;
            padding: 60px 50px;
            background-color: white;
            transition: margin-left .3s ease;
        }

        .main-content h1 {
            color: #ff6600;
            font-size: 50px;
            font-weight: 400;
        }

        .main-content p {
            color: #ff6600;
            font-size: 16px;
            margin-top: 8px;
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-top: 25px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .card i {
            font-size: 30px;
            color: #ff6600;
        }

        .card h3 {
            margin-top: 10px;
            font-size: 16px;
            font-weight: 600;
            color: #444444;
        }

        .card p {
            font-size: 28px;
            margin-top: 10px;
            color: #ff6600;
        }


        .avisos-recientes {
            margin-top: 20px;
        }

        .aviso-item {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            font-size: 16px;
        }

        .aviso-item strong {
            color: #333;
        }

        .aviso-item span {
            color: #ff6600;
            font-weight: 600;
        }

        .avisos-recientes p {
            color: black;
        }
    </style>
</head>
<body>
    <button class="toggle-btn" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </button>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Bienvenido, 
            <strong>
                <?php
                $nombreCompleto = $_SESSION['nombre'];
                $partes = explode(" ", trim($nombreCompleto));

                $primerNombre = $partes[0];
                $primerApellido = isset($partes[2]) ? $partes[2] : "";

                echo htmlspecialchars($primerNombre . " " . $primerApellido);
                ?>
            </strong>
        </h1>

        <div class="dashboard-cards">
            <div class="card">
                <i class="fa-solid fa-users"></i>
                <h3>Usuarios registrados</h3>
                <p><?php echo $usuarios; ?></p>
            </div>

            <div class="card">
                <i class="fa-solid fa-calendar-check"></i>
                <h3>Reservas hoy</h3>
                <p><?php echo $reservasHoy; ?></p>
            </div>

            <div class="card">
                <i class="fa-solid fa-door-open"></i>
                <h3>Cubículos disponibles</h3>
                <p><?php echo $cubiculosDisponibles; ?></p>
            </div>

            <div class="card">
                <i class="fa-solid fa-bell"></i>
                <h3>Avisos activos</h3>
                <p><?php echo $avisosActivos; ?></p>
            </div>
        </div>


        <h2 style="margin-top:40px; color:#ff6600;">Avisos recientes</h2>
        <div class="avisos-recientes">
            <?php 
            if ($avisosRecientesSQL->num_rows > 0) {
                while ($row = $avisosRecientesSQL->fetch_assoc()) {
                    $titulo = htmlspecialchars($row['titulo']);
                    $fecha = date("d M Y", strtotime($row['fecha']));
                    echo "
                    <div class='aviso-item'>
                        <strong>$titulo</strong>
                        <span style='color:#ff6600; font-weight:600;'>$fecha</span>
                    </div>";
                }
            } else {
                echo "<p>No hay avisos disponibles.</p>";
            }
            ?>
        </div>

    </div>

</body>
</html>
