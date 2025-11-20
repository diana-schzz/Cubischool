<?php
session_start();

if (!isset($_SESSION['nombre']) || $_SESSION['tipoUsuario'] != 3) {
    header("Location: ../login_user.php");
    exit();
}

require_once "../../config/conexion.php";

$datosUsuario = null;
$mensaje = null;
$idBuscado = "";

$camposPermitidos = ['nombre', 'correoInstitucional', 'contrasena', 'idIest'];

/* ----- ACTUALIZAR DESDE EL MODAL ----- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {

    $idIest = trim($_POST['idIest'] ?? '');
    $campo  = $_POST['campo'] ?? '';
    $nuevoValor = $_POST['nuevoValor'] ?? '';

    if ($idIest === '' || $campo === '' || $nuevoValor === '') {
        $mensaje = "Faltan datos para actualizar.";
    } elseif (!in_array($campo, $camposPermitidos, true)) {
        $mensaje = "Campo no permitido.";
    } else {

        if ($campo === 'contrasena') {
            $nuevoValor = password_hash($nuevoValor, PASSWORD_BCRYPT);
        }

        $sql = "UPDATE usuario SET $campo = ? WHERE idIest = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ss', $nuevoValor, $idIest);

        if ($stmt->execute()) {
            $mensaje = "Datos actualizados correctamente.";
            $idBuscado = $idIest; 
        } else {
            $mensaje = "Error al actualizar: " . $stmt->error;
        }

        $stmt->close();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])) {
    $idBuscado = trim($_POST['idIest']);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['idIest'])) {
    $idBuscado = trim($_GET['idIest']);
}

if ($idBuscado !== "") {
    $query = "SELECT idIest, nombre, correoInstitucional, tipoUsuario FROM usuario WHERE idIest = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $idBuscado); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $datosUsuario = $result->fetch_assoc();
    } else {
        $mensaje = "No se encontró ningún usuario con el ID proporcionado.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CubiSchool</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f7f7f7;
            font-family: "Poppins", sans-serif;
        }

        .main-content {
            flex: 1;
            padding: 60px 50px;
            background-color: #ffffff;
            transition: margin-left .3s ease;
        }

        h1 {
            font-size: 40px;
            color: #ff6600;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .sub {
            color: black;
            margin-bottom: 15px;
        }

        .form-buscar {
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .form-buscar input[type="text"] {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            width: 220px;
        }

        .form-buscar button {
            background-color: #ff6600;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
        }

        .form-buscar button:hover {
            background-color: #e35b00;
        }

        .mensaje-error {
            margin-top: 10px;
            color: red;
            font-size: 15px;
        }

        table {
            width: 100%;
            margin: 0 auto; 
            border-collapse: collapse;
            background: white;
            margin-top: 25px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        table th, table td {
            padding: 11px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        table th {
            background-color: #eeeeee; 
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-size: 18px;
            padding: 13px;
            text-align: left;
        }

        table tr {
            font-weight: 300;
            font-size: 18px;
        }

        table tr:hover {
            background-color: #f7f7f7;
        }

        .container-buttons {
            margin-top: 18px;
        }

        .btn-editar {
            background-color: #60421F;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-editar:hover {
            background-color: #3f2c14;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.4);
            backdrop-filter: blur(2px);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #ffffff;
            padding: 25px 30px;
            border-radius: 15px;
            width: 420px;
            position: relative;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .modal-content h2 {
            color: #ff6600;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .modal-content label {
            display: block;
            font-size: 15px;
            margin-top: 10px;
            margin-bottom: 4px;
            color: #333;
        }

        .modal-content select,
        .modal-content input[type="text"],
        .modal-content input[type="email"],
        .modal-content input[type="password"] {
            width: 100%;
            padding: 9px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 18px;
        }

        .btn-cerrar {
            background-color: #aaa;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-guardar {
            background-color: #ff6600;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-guardar:hover { background-color: #e35b00; }
        .btn-cerrar:hover { background-color: #888; }

        .modal-close-x {
            position: absolute;
            top: 8px;
            right: 12px;
            font-size: 22px;
            color: #ff6600;
            cursor: pointer;
        }

        .modal-close-x:hover {
            color: #60421F;
        }

    </style>
</head>
<body>
    <?php include '../../includes/sidebar.php'; ?>

<div class="main-content">
    <h1>Modificar datos de usuario</h1>
    <p class="sub">Ingrese ID del usuario</p>

    <form action="" method="POST" class="form-buscar">
        <input type="text" id="idIest" name="idIest" placeholder="ID IEST" 
               value="<?php echo htmlspecialchars($idBuscado); ?>" required>
        <button type="submit" name="buscar">
            <i class="fa-solid fa-magnifying-glass"></i> Buscar
        </button>
    </form>

    <?php if ($mensaje): ?>
        <div class="mensaje-error">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <?php if ($datosUsuario): ?>
        <table>
            <thead>
                <tr>
                    <th>ID IEST</th>
                    <th>Nombre</th>
                    <th>Correo institucional</th>
                    <th>Tipo de usuario</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($datosUsuario['idIest']); ?></td>
                    <td><?php echo htmlspecialchars($datosUsuario['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($datosUsuario['correoInstitucional']); ?></td>
                    <td>
                        <?php
                        $labelTipo = [
                            1 => "Alumno",
                            2 => "Secretaria",
                            3 => "Administrador"
                        ];
                        echo $labelTipo[$datosUsuario['tipoUsuario']] ?? "Desconocido";
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="container-buttons">
            <button type="button" class="btn-editar" id="openModal">
                <i class="fa-solid fa-pen-to-square"></i> Modificar datos
            </button>
        </div>

        <div id="modaledit" class="modal">
            <div class="modal-content">
                <span class="modal-close-x" id="closeModal">&times;</span>
                <h2>Modificar usuario</h2>

                <form action="" method="post">
                    <input type="hidden" name="actualizar" value="1">
                    <input type="hidden" name="idIest" value="<?php echo htmlspecialchars($datosUsuario['idIest']); ?>">

                    <label for="campo">Seleccionar campo a modificar:</label>
                    <select name="campo" id="campo" required>
                        <option value="nombre">Nombre</option>
                        <option value="correoInstitucional">Correo institucional</option>
                        <option value="contrasena">Contraseña</option>
                        <option value="idIest">ID IEST</option>
                    </select>

                    <label for="nuevoValor">Nuevo valor:</label>
                    <input type="text" id="nuevoValor" name="nuevoValor" required>

                    <input type="hidden" name="idIest" value="<?php echo htmlspecialchars($datosUsuario['idIest']); ?>">

                    <div class="modal-buttons">
                        <button type="button" class="btn-cerrar" id="btnCancelar">Cancelar</button>
                        <button type="submit" class="btn-guardar">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

</div>

<script>
<?php if ($datosUsuario): ?>
    const modal = document.getElementById("modaledit");
    const btnOpen = document.getElementById("openModal");
    const btnCloseX = document.getElementById("closeModal");
    const btnCancelar = document.getElementById("btnCancelar");

    btnOpen.onclick = function() {
        modal.style.display = "flex";
    }

    btnCloseX.onclick = function() {
        modal.style.display = "none";
    }

    btnCancelar.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
<?php endif; ?>
</script>
</body>
</html>