<?php
session_start();

if (!isset($_SESSION['nombre']) || $_SESSION['tipoUsuario'] != 1) {
    header("Location: ../login_user.php");
    exit;
}

require_once "../../config/conexion.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/cubi/assets/css/calendario.css">
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

        .main-content h1{
            color:#ff6600;
            font-size:40px;
            font-weight:600;
        }

        .subtitle{
            color:#555;
            font-size:15px;
            margin-bottom: 30px;
        }

        .reservation-layout {
            display: flex;
            gap: 60px;
            align-items: flex-start;
            margin-top: 25px;
        }

        .left-panel {
        }

        .right-panel {
            flex: 1;
            max-width: 400px;
            min-width: 300px;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            padding-top: 0;
        }


        .options-panel {
            width: 100%;
            max-width: 650px;
        }


        .btn-primary {
            background-color: #ff6600;
            color: #fff;
            border: none;
            padding: 12px 40px;
            border-radius: 8px;
            font-size: 16px;
            width: 400px;
            font-weight: 500;
            margin-top: 30px;
            cursor: pointer;
            transition: background-color .2s ease, transform .1s ease;
        }

        .btn-primary:hover {
            background-color: #e35b00;
            transform: translateY(-1px);
        }

        .field-group {
            margin-bottom: 22px;
        }

        .section-title {
            font-size: 17px;
            font-weight: 500;
            color: #303030;
            margin-bottom: 6px;
        }

        .section-title small {
            color: red;
            margin-left: 2px;
        }

        input[type="number"]{
            width:100%;
            height:42px;
            padding:8px 10px;
            border-radius:8px;
            border:1px solid #C5C5C5;
            font-family:"Roboto",sans-serif;
            font-size:14px;
            margin-bottom: 20px;
        }

        .small-hint{
            font-size:13px;
            color:#777;
            margin-top:4px;
        }

        .calendar-wrapper{margin-top:10px;}

        .hidden{display:none;}

        .select-group{margin-bottom:40px;}

        .select-group select{
            width:100%;
            height:42px;
            padding:8px 10px;
            border-radius:8px;
            border:1px solid #C5C5C5;
            font-family:"Roboto",sans-serif;
        }

        .time-slots{
            display:flex;
            flex-wrap:wrap;
            gap:8px;
            margin-top:8px;
        }

        .time-slot{
            border:1px solid #ff6600;
            background:#fff;
            color:#ff6600;
            padding:7px 13px;
            border-radius:6px;
            font-size:14px;
            cursor:pointer;
            font-family:"Poppins",sans-serif;
        }

        .time-slot.selected{
            background:#ff6600;
            color:#fff;
        }

        .time-slot.disabled{
            border-color:#ccc;
            color:#aaa;
            cursor:not-allowed;
        }

        .reservation-details h3{
            font-size:18px;
            margin-top:50px;
            margin-bottom:10px;
            font-weight: 500;
            color:#303030;
        }

        .reservation-details input[type="text"]{
            width:100%;
            height:42px;
            padding:8px 10px;
            border-radius:8px;
            border:1px solid #C5C5C5;
            font-family:"Roboto",sans-serif;
            margin-bottom:12px;
        }

        .reservation-details label{
            font-family:"Roboto",sans-serif;
            font-size:14px;
            display:flex;
            align-items:center;
            gap:8px;
            color:#444;
        }

        .btn-reserve{
            margin-top:50px;
            background:#ff6600;
            color:#fff;
            border:none;
            border-radius:8px;
            height:42px;
            width:100%;
            font-size:15px;
            font-family:"Poppins",sans-serif;
            cursor:pointer;
        }

    </style>
</head>
<body>

    <button class="toggle-btn" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </button>

    <?php include '../../includes/sidebar.php'; ?>
    <?php include '../../includes/modal_contacto.php'; ?>

    <div class="main-content">
        <h1>Reservar cubículo</h1>
        <p class="subtitle">
            Elige cuántas personas son, una fecha y te mostraremos qué cubículos se ajustan y qué horarios hay disponibles.
        </p>

        <div class="reservation-layout">
            <div class="left-panel">
                <div class="field-group">
                    <div class="section-title">
                        Número de personas <small>*</small>
                    </div>

                    <input type="number" id="numPersonas" min="1" max="10"
                        placeholder="Ej. 2, 3, 6, 10...">
                </div>

                <div class="field-group">
                    <div class="section-title">
                        Seleccionar fecha <small>*</small>
                    </div>

                    <div class="calendar-wrapper">
                        <div class="wrapper">
                            <header>
                                <p class="current-date"></p>
                                <div class="icons">
                                    <span id="prev">&#8249;</span>
                                    <span id="next">&#8250;</span>
                                </div>
                            </header>

                            <div class="calendar">
                                <ul class="weeks">
                                    <li>Lu</li><li>Ma</li><li>Mi</li>
                                    <li>Ju</li><li>Vi</li><li>Sa</li><li>Do</li>
                                </ul>
                                <ul class="days"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-panel">
                <button id="verDisponibilidad" class="btn-primary">
                    Ver disponibilidad
                </button>
                <div class="options-panel hidden" id="opcionesReserva">
                    <form action="guardar_reserva.php" method="post" id="formReserva">
                        <div class="section-title">
                            Cubículo disponible <small>*</small>
                        </div>
                        <div class="select-group">
                            <select name="id_cubiculo" id="selectCubiculo">
                                <option value="">Selecciona un cubículo...</option>
                            </select>
                        </div>

                        <div class="section-title">
                            Horario (1 hora) <small>*</small>
                        </div>
                        <div class="time-slots" id="listaHorarios">

                        </div>


                        <div class="reservation-details">
                            <h3>Detalles de la reserva</h3>

                            <input type="text" name="nombre_reserva"
                                placeholder="Nombre del responsable"
                                value="<?= htmlspecialchars($_SESSION['nombre'] ?? '') ?>">

                            <label>
                                <input type="checkbox" name="plumones" value="1">
                                Plumones para el pizarrón
                            </label>

                            <input type="hidden" name="fecha" id="fechaSeleccionadaInput">
                            <input type="hidden" name="hora" id="horaSeleccionadaInput">
                            <input type="hidden" name="num_personas" id="numPersonasInput">

                            <button type="submit" class="btn-reserve" id="btnApartar">
                                Apartar cubículo
                            </button>
                        </div>
                    </form>
                </div>
        </div>
    </div>

<script src="../../assets/js/reserva.js"></script>
<script src="../../assets/js/scripts.js"></script>
<script src="../../assets/js/modal.js"></script>


</body>
</html>
