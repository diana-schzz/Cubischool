<?php
session_start();

if (!isset($_SESSION['nombre']) || $_SESSION['tipoUsuario'] != 3) {
    header("Location: ../login_user.php");
    exit();
}

require_once "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_usuario'])) {

    $nombre = trim($_POST['nombre']);
    $idIest = trim($_POST['idIest']);
    $correo = trim($_POST['correoInstitucional']);
    $tipoUsuario = intval($_POST['tipoUsuario']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $mysqli->prepare(
        "INSERT INTO usuario (nombre, idIest, tipoUsuario, contrasena, correoInstitucional) 
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssiss", $nombre, $idIest, $tipoUsuario, $password, $correo);
    $stmt->execute();
    $stmt->close();

    header("Location: usuarios.php");
    exit();
}

$query = "SELECT nombre, idIest, tipoUsuario, correoInstitucional FROM usuario ORDER BY nombre ASC";
$result = $mysqli->query($query);

$tipos = [
    1 => "Alumno",
    2 => "Secretaría",
    3 => "Administrador"
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CubiSchool</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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

        .main-content h2 {
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            color: #ff6600;
            font-size: 40px;
        }

        .top-buttons {
            display: flex;
            justify-content: flex-start;
            gap: 15px;
            margin-bottom: 25px;
        }

        .btn {
            padding: 10px 18px;
            border-radius: 10px;
            cursor: pointer;
            border: none;
            font-size: 16px;
            font-weight: 500;
            margin-top: 30px;
        }

        .btn-add {
            background: #ff6600;
            color: white;
        }

        .btn-add:hover {
            background: #e35b00;
        }

        .btn-filter {
            background: #444;
            color: white;
        }

        .btn-filter:hover {
            background: #222;
        }


        p, small {
            font-family: "Poppins", sans-serif;
            font-weight: 300;
            font-size: 16px;
            color: #666;
            margin: 0;
        }

        
        .info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            flex-grow: 1;
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
            border: 1px solid #E5E5E5; 
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

        table tr:nth-child(even) { 
            background-color: #f9f9f9; 
        }

        table tr {
            font-weight: 300;
            font-size: 18px;
        }

        tr:hover {
            background-color: #f0f0f0ff;
        }

        .delete-btn {
            color: red;
            cursor: pointer;
            font-size: 20px;
        }

        #searchInput {
            width: 280px;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        td.acciones {
            text-align: center;
        }

        .delete-btn:hover {
            color: #b30000;
            transform: scale(1.2);
            transition: 0.2s ease;
        }

        /* 🔶 FONDO OSCURO */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(3px);
            align-items: center;
            justify-content: center;
        }

        /* 🔶 CAJA DEL MODAL */
        .modal-content {
            background: white;
            padding: 30px;
            width: 400px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .modal-content h3 {
            font-family: "Poppins", sans-serif;
            margin-bottom: 20px;
            color: #ff6600;
            font-size: 25px;
            text-align: center;
        }

        .modal-content label {
            font-weight: 400;
            font-family: "Poppins", sans-serif;
        }

        .modal-content input,
        .modal-content select {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .modal-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .btn-cancel {
            background: #aaa;
            border: none;
            padding: 10px 18px;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-save {
            background: #ff6600;
            border: none;
            padding: 10px 18px;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-save:hover { background: #e35b00; }
        .btn-cancel:hover { background: #888; }


    </style>
</head>

<body>

<?php include '../../includes/sidebar.php'; ?>

<div class="main-content">

    <h2>Administrar usuarios</h2>

    <div class="top-buttons">
        <button class="btn btn-add" onclick="abrirModal()">
            <i class="fa-solid fa-user-plus"></i> Agregar usuario
        </button>


        <button class="btn btn-filter" onclick="toggleSearch()">
            <i class="fa-solid fa-filter"></i> Filtrar
        </button>
    </div>

    <input type="text" id="searchInput" placeholder="Buscar usuario..." style="display:none;" onkeyup="filterTable()">

    <?php if (isset($_GET['deleted'])): ?>
        <p style="color: green; margin-top:10px;">Usuario eliminado correctamente.</p>
    <?php endif; ?>


    <table id="tablaUsuarios">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>ID IEST</th>
                <th>Correo</th>
                <th>Tipo Usuario</th>
                <th style="width: 80px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['idIest']) ?></td>
                    <td><?= htmlspecialchars($row['correoInstitucional']) ?></td>
                    <td><?= $tipos[$row['tipoUsuario']] ?></td>

                    <td class="acciones">
                        <i class="fa-solid fa-trash delete-btn"
                        onclick="confirmDelete(<?= json_encode($row['idIest']) ?>, <?= json_encode($row['nombre']) ?>)"></i>
                    </td>


                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

<div id="modalAgregar" class="modal">
    <div class="modal-content">
        <h3>Agregar nuevo usuario</h3>

        <form method="POST">
            <input type="hidden" name="agregar_usuario" value="1">

            <label>Nombre completo</label>
            <input type="text" name="nombre" required>

            <label>ID IEST</label>
            <input type="text" name="idIest" required>

            <label>Correo institucional</label>
            <input type="email" name="correoInstitucional" required>

            <label>Tipo de usuario</label>
            <select name="tipoUsuario" required>
                <option value="1">Alumno</option>
                <option value="2">Secretaria</option>
                <option value="3">Administrador</option>
            </select>

            <label>Contraseña temporal</label>
            <input type="password" name="password" required>

            <div class="modal-buttons">
                <button type="button" class="btn-cancel" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-save">Guardar</button>
            </div>
        </form>
    </div>
</div>



<script>
function toggleSearch() {
    const input = document.getElementById("searchInput");
    input.style.display = (input.style.display === "none") ? "block" : "none";
}

function filterTable() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll("#tablaUsuarios tbody tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}

function confirmDelete(id, nombre) {
    alert("ID que voy a mandar: " + id);

    if (confirm(`¿Seguro que quieres eliminar a: ${nombre}?`)) {
        alert("URL final: " + "/cubi/dashboards/admin/eliminar_usuario.php?id=" + encodeURIComponent(id));
        window.location.href = "/cubi/dashboards/admin/eliminar_usuario.php?id=" + encodeURIComponent(id);
    }
}


function abrirModal() {
    document.getElementById("modalAgregar").style.display = "flex";
}

function cerrarModal() {
    document.getElementById("modalAgregar").style.display = "none";
}

window.onclick = function(e) {
    let modal = document.getElementById("modalAgregar");
    if (e.target === modal) modal.style.display = "none";
};

</script>

</body>
</html>