<?php
session_start();

if (!isset($_SESSION['idIest']) || $_SESSION['tipoUsuario'] != 1) {
    header("Location: ../login_user.php");
    exit();
}

require_once "../../config/conexion.php";

$idIest = $_SESSION['idIest'];

$sql = "
    SELECT 
        r.idReserva,
        r.idIest,
        r.numCubiculo,
        r.fecha,
        r.horaInicio,
        r.horaFin,
        r.estado,
        r.material,

        CASE 
            WHEN r.estado = 3 THEN 'cancelada'
            WHEN r.fecha < CURDATE() THEN 'finalizada'
            WHEN r.fecha = CURDATE() AND r.horaFin < CURTIME() THEN 'finalizada'
            ELSE 'activa'
        END AS estado_real

    FROM reservas r
    WHERE r.idIest = ?
    ORDER BY r.fecha DESC, r.horaInicio DESC

";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $idIest);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Reservas</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="/cubi/assets/css/modal.css">

    <style>
        *{margin:0;
            padding:0;
            box-sizing:border-box;}

        body{
            display:flex;
            min-height:100vh;
            background:#f7f7f7;
            font-family:"Poppins",sans-serif;
        }

        .main-content{
            flex:1;
            padding:60px 50px;
            background:#fff;
            transition:margin-left .3s ease;
        }

        h1 {
            font-size: 38px;
            font-weight: 600;
            color: #ff6600;
            margin-bottom: 25px;
        }

        .reserva-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 18px;
            box-shadow: 0 4px 12px rgb(0 0 0 / 8%);
            transition: transform .15s ease;
            border-left: 8px solid transparent;
        }

        .reserva-card:hover {
            transform: translateY(-2px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .cubiculo {
            font-size: 20px;
            font-weight: 600;
            color: #303030;
        }

        .estado {
            font-size: 14px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 6px;
        }

        .estado-activa {
            background: #e7f9e7;
            color: #2d8a2d;
        }

        .estado-finalizada {
            background: #ffe5e5;
            color: #cc0000;
        }

        .estado-cancelada {
            background: #e6e6e6;
            color: #666;
        }

        .detalle {
            font-size: 15px;
            color: #444;
            margin-bottom: 5px;
        }

        .material-tag {
            background: #ff6600;
            color: white;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
            margin-top: 8px;
        }

        .no-reservas {
            margin-top: 20px;
            padding: 20px;
            background: #fff7e6;
            border-radius: 10px;
            color: #a66a00;
            font-size: 16px;
            box-shadow: 0 4px 10px rgb(0 0 0 / 6%);
        }
    </style>
</head>
<body>

<?php include '../../includes/sidebar.php'; ?>
<?php include '../../includes/modal_contacto.php'; ?>


<div class="main-content">
    <h1>Mis Reservas</h1>

    <?php if ($result->num_rows > 0): ?>
        
        <?php while ($row = $result->fetch_assoc()): ?>
            
            <?php
            $estado = $row['estado_real'];

            $claseEstado = [
                "activa" => "estado-activa",
                "finalizada" => "estado-finalizada",
                "cancelada" => "estado-cancelada"
            ][$estado];
            ?>

            <div class="reserva-card" style="border-left-color:
                <?= ($estado == 'activa' ? '#2d8a2d' : ($estado == 'finalizada' ? '#cc0000' : '#666')) ?>">

                <div class="card-header">
                    <div class="cubiculo">Cubículo <?= $row['numCubiculo'] ?></div>
                    <div class="estado <?= $claseEstado ?>">
                        <?= ucfirst($estado) ?>
                    </div>
                </div>

                <div class="detalle">
                    <i class="fa-regular fa-calendar" style="margin-right:6px; color:#ff6600;"></i>
                    <strong>Fecha:</strong> 
                    <?= date("d/m/Y", strtotime($row['fecha'])) ?>
                </div>


                <div class="detalle">
                    <i class="fa-regular fa-clock" style="margin-right:6px; color:#ff6600;"></i>
                    <strong>Hora:</strong> 
                    <?= substr($row['horaInicio'], 0, 5) ?> – <?= substr($row['horaFin'], 0, 5) ?>
                </div>


                <?php if (!empty($row['material'])): ?>
                    <span class="material-tag">Plumones solicitados</span>
                <?php endif; ?>

                <?php if ($estado === "activa"): ?>
                    <form action="cancelar_reserva.php" method="POST" style="margin-top:15px;">
                        <input type="hidden" name="idReserva" value="<?= $row['idReserva'] ?>">
                        <button type="submit" 
                            onclick="return confirm('¿Seguro que deseas cancelar esta reserva?');"
                            style="
                                background:#cc0000;
                                color:white;
                                padding:6px 10px;
                                border:none;
                                border-radius:6px;
                                cursor:pointer;
                                font-size:13px;
                                font-weight: 500;
                                width: 160px;
                            ">
                            Cancelar reserva
                        </button>
                    </form>
                <?php endif; ?>


            </div>

        <?php endwhile; ?>

    <?php else: ?>
        <div class="no-reservas">
            No tienes reservas registradas. Cuando apartes un cubículo, aparecerá aquí.
        </div>
    <?php endif; ?>

</div>
<script src="/cubi/assets/js/modal.js"></script>
</body>
</html>
