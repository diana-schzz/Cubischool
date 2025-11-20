<?php
session_start();

if (!isset($_SESSION['idIest']) || $_SESSION['tipoUsuario'] != 1) {
    header("Location: ../login_user.php");
    exit();
}

require_once "../../config/conexion.php";

$idIest = $_SESSION['idIest'];

$query = "
    SELECT nombre, correoInstitucional, tipoUsuario, fotoPerfil
    FROM usuario
    WHERE idIest = ?
";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $idIest);
$stmt->execute();
$result = $stmt->get_result();
$datos = $result->fetch_assoc();

$nombre = $datos['nombre'];
$correo = $datos['correoInstitucional'];
$tipo   = $datos['tipoUsuario'];
$fotoBD = $datos['fotoPerfil'] ?? null;

$labelTipo = [
    1 => "Alumno",
    2 => "Secretaría",
    3 => "Administrador"
];

$fotoBD = $datos['fotoPerfil'] ?? '';

if (empty($fotoBD) || $fotoBD === 'delfin.png') {
    $foto = "/cubi/assets/img/delfin.png";
} else {
    $foto = "/cubi/uploads/perfiles/" . $fotoBD;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="/cubi/assets/css/modal.css">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

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
            display:flex;
            justify-content:center;   
            align-items:center;  
        }

        .profile-card {
            background: #ffffff;
            border-radius: 25px;
            padding: 45px 50px;
            width: 100%;
            max-width: 1200px;       
            box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        }

        .profile-header-row{
            display:flex;
            align-items:flex-start;
            gap:30px;
            margin-bottom:25px;
        }

        .profile-texts{
            flex:1;
        }

        h1 {
            font-size: 38px;
            font-weight: 600;
            color: #ff6600;
            margin-bottom:5px;
        }

        .subtitle {
            color: #555;
            margin-bottom: 8px;
        }

        .role-tag{
            display:inline-block;
            background:#ffe7d6;
            color:#ff6600;
            padding:4px 10px;
            border-radius:999px;
            font-size:13px;
            font-weight:500;
            margin-top:5px;
        }

        .photo-wrapper{
            position:relative;
            width:150px;
            height:150px;
            flex-shrink:0;
        }

        .profile-photo{
            width:100%;
            height:100%;
            object-fit:cover;
            border-radius:50%;
            border:5px solid #fff;
            box-shadow:0 4px 10px rgba(0,0,0,0.15);
            cursor:pointer;
        }

        .photo-edit-btn{
            position:absolute;
            right:0;
            bottom:0;
            width:42px;
            height:42px;
            border-radius:50%;
            border:none;
            background:#ff6600;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            cursor:pointer;
            box-shadow:0 3px 8px rgba(0,0,0,0.25);
            transition:background .2s ease, transform .1s ease;
        }

        .photo-edit-btn i{
            font-size:18px;
        }

        .photo-edit-btn:hover{
            background:#e35b00;
            transform:translateY(-1px);
        }

        .photo-hint{
            font-size:12px;
            color:#777;
            margin-top:6px;
            text-align:center;
        }

        .btn-save-photo{
            margin-top:12px;
            background:#ff6600;
            color:#fff;
            border:none;
            border-radius:10px;
            padding:8px 18px;
            font-size:14px;
            font-weight:500;
            cursor:pointer;
            display:inline-block;
        }

        .btn-save-photo:hover{
            background:#e35b00;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px 60px;
            margin-top: 10px;
        }

        .info-item {
            display: flex;
            gap: 13px;
            align-items: flex-start;
        }

        .info-item i {
            font-size: 22px;
            color: #ff6600;
            margin-top: 3px;
        }

        .info-label {
            font-weight: 600;
            font-size: 15px;
        }

        .info-value {
            font-weight: 400;
            display: block;
            color: #444;
            margin-top: 4px;
            font-size:14px;
        }

        @media (max-width: 900px){
            .main-content{
                padding:20px;
                align-items:flex-start;
            }

            .profile-card{
                padding:30px 20px;
            }

            .profile-header-row{
                flex-direction:column;
                align-items:center;
                text-align:center;
            }

            .info-grid{
                grid-template-columns:1fr;
            }
        }
    </style>
</head>

<body>

<?php include '../../includes/sidebar.php'; ?>
<?php include '../../includes/modal_contacto.php'; ?>


<div class="main-content">
    <div class="profile-card">

        <div class="profile-header-row">
            <div>
                <form action="subir_foto.php" method="POST" enctype="multipart/form-data" id="formFoto">
                    <div class="photo-wrapper">
                        <img src="<?= htmlspecialchars($foto) ?>" 
                             alt="Foto de perfil" 
                             class="profile-photo" 
                             id="profilePreview">

                        <button type="button" class="photo-edit-btn" id="btnCambiarFoto">
                            <i class="fa-solid fa-camera"></i>
                        </button>
                    </div>

                    <input type="file" name="foto" id="inputFoto" 
                           accept="image/png, image/jpeg" 
                           style="display:none;">

                    <div class="photo-hint">
                        Haz clic en la foto o en el ícono para cambiar tu imagen.
                    </div>

                    <button type="submit" class="btn-save-photo" id="btnGuardarFoto" style="display:none;">
                        Guardar foto
                    </button>
                </form>
            </div>

            <div class="profile-texts">
                <h1>Mi Perfil, <span style="color:#222;"><?= htmlspecialchars(explode(' ', trim($nombre))[0]) ?></span></h1>
                <p class="subtitle">Instituto de Estudios Superiores de Tamaulipas</p>
                <span class="role-tag"><?= $labelTipo[$tipo] ?></span>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <i class="fa-solid fa-user"></i>
                <div>
                    <span class="info-label">Nombre completo</span>
                    <span class="info-value"><?= htmlspecialchars($nombre) ?></span>
                </div>
            </div>

            <div class="info-item">
                <i class="fa-solid fa-id-card"></i>
                <div>
                    <span class="info-label">ID IEST</span>
                    <span class="info-value"><?= htmlspecialchars($idIest) ?></span>
                </div>
            </div>

            <div class="info-item">
                <i class="fa-solid fa-envelope"></i>
                <div>
                    <span class="info-label">Correo institucional</span>
                    <span class="info-value"><?= htmlspecialchars($correo) ?></span>
                </div>
            </div>

            <div class="info-item">
                <i class="fa-solid fa-user-shield"></i>
                <div>
                    <span class="info-label">Tipo de usuario</span>
                    <span class="info-value"><?= $labelTipo[$tipo] ?></span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const btnCambiar  = document.getElementById('btnCambiarFoto');
    const inputFoto   = document.getElementById('inputFoto');
    const preview     = document.getElementById('profilePreview');
    const btnGuardar  = document.getElementById('btnGuardarFoto');

    if(!btnCambiar || !inputFoto || !preview) return;

    btnCambiar.addEventListener('click', () => inputFoto.click());
    preview.addEventListener('click',      () => inputFoto.click());

    inputFoto.addEventListener('change', function(e){
        const file = e.target.files[0];
        if(!file) return;

        if(!file.type.startsWith('image/')){
            alert('Por favor selecciona una imagen válida (JPG o PNG).');
            inputFoto.value = "";
            return;
        }

        const reader = new FileReader();
        reader.onload = function(ev){
            preview.src = ev.target.result; 
        };
        reader.readAsDataURL(file);

        btnGuardar.style.display = 'inline-block';
    });
});
</script>
<script src="/cubi/assets/js/modal.js"></script>

</body>
</html>

