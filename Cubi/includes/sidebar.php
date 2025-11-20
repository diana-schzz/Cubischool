<?php
if (!isset($_SESSION)) {
    session_start();
}

$tipo = $_SESSION['tipoUsuario'] ?? null;
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<style>
    .menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .menu li {
        margin: 10px 0;
    }

    .menu a, .logout a {
        font-family: "Poppins", sans-serif;
        text-decoration: none;
        color: white;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 13px;
        width: 100%;
        padding: 12px 25px;
        transition: background 0.2s;
    }

    .menu a:hover {
            background-color: rgba(255, 255, 255, 0.15);
    }

    .menu i {
        font-size: 23px; 
        color: white;
        width: 30px;
    }

    .menu a.active {
        background-color: rgba(255, 255, 255, 0.25);
    }

    .menu a.active i {
        color: #ff6600;
    }

    .sidebar:hover .menu a.active span {
        opacity: 1;
    }

    .menu a.active span {
        color: #ff6600;
    }


    .sidebar h2 {
        color: white;
        font-family: "Poppins", sans-serif;
        font-weight: 300;
        text-align: center;
        font-size: 30px;
        margin: 0 auto;
        display: block;
        margin-bottom: 50px; 
        opacity: 0;
        width: 0;
        overflow: hidden;
        white-space: nowrap;
        transition: opacity .2s ease, width .2s ease;
    }
    
    .sidebar:hover h2 {
        opacity: 1;
        width: auto;
    }


    .logout {
        margin-top: 150px;
    }

    .logout i {
        font-size: 25px; 
        color: white;
    }

    .toggle-btn {
        position: fixed;
        top: 20px;
        left: 20px;
        background: #ff6600;
        border: none;
        padding: 12px 14px;
        border-radius: 10px;
        color: white;
        font-size: 20px;
        cursor: pointer;
        z-index: 1001;
        display: none; 
    }

    .toggle-btn i {
        font-size: 22px;
    }


    .sidebar {
        transition: all 0.2s ease;
        width: 70px;
        background-color: #2F2F2F;
        color: white;
        flex-direction: column;
        align-items: center;
        height: 100vh;
        padding-top: 30px;
    }

    .sidebar:hover {
        width: 220px;         
    }

    @media (max-width: 900px) {

        .sidebar {
            position: fixed;
            left: -280px; 
            top: 0;
            height: 100vh;
            width: 260px;
            background-color: #2F2F2F;
            padding: 40px 20px;
            z-index: 1000;
        }

        .sidebar.active {
            left: 0;      
        }

        .toggle-btn {
            display: block;
        }

        .main-content {
            margin-left: 0 !important;
        }
    }

    .menu a span,
    .logout a span {
        opacity: 0;
    }

    .sidebar:hover .menu a span,
    .sidebar:hover .logout a span {
        opacity: 1;
    }

</style>
<script>
function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('active');
}
</script>

<body>
    <div class="sidebar">
        <h2 onclick="window.location.href='inicio.php'" style="cursor:pointer;">CUBISCHOOL</h2>
    <ul class="menu">
         <?php if ($tipo == 1): ?>
            <li>
                <a href="reservar.php"
                class="<?= ($currentPage === 'reservar.php' ? 'active' : '') ?>">
                    <i class="fa-solid fa-bookmark"></i>
                    <span>Reservar</span>
                </a>
            </li>

            <li>
                <a href="mis_reservas.php"
                class="<?= ($currentPage === 'mis_reservas.php' ? 'active' : '') ?>">
                    <i class="fa-solid fa-book-bookmark"></i>
                    <span>Mis Reservas</span>
                </a>
            </li>

            <li>
                <a href="avisos.php"
                class="<?= ($currentPage === 'avisos.php' ? 'active' : '') ?>">
                    <i class="fa-solid fa-bell"></i>
                    <span>Avisos</span>
                </a>
            </li>

            <li>
                <a href="perfil.php"
                class="<?= ($currentPage === 'perfil.php' ? 'active' : '') ?>">
                    <i class="fa-solid fa-user"></i>
                    <span>Mi Perfil</span>
                </a>
            </li>

            <li>
                <a href="contacto.php"
                class="<?= ($currentPage === 'contacto.php' ? 'active' : '') ?>">
                    <i class="fa-solid fa-circle-question"></i>
                    <span>Contáctanos</span>
                </a>
            </li>
        <?php endif; ?>


        <?php if ($tipo == 2): ?>
            <li><a href="reservar.php"><i class="fa-solid fa-bookmark"></i>Reservar</a></li>
            <li><a href="mis_reservas.php"><i class="fa-solid fa-book-bookmark"></i> Mis Reservas</a></li>
            <li><a href="avisos.php"><i class="fa-solid fa-bell"></i></i> Avisos</a></li>
            <li><a href="perfil.php"><i class="fa-solid fa-user"></i> Mi Perfil</a></li>
            <li><a href="contacto.php"><i class="fa-solid fa-circle-question"></i> Contáctanos</a></li>
            <li><a href="mis_reservas.php"><i class="fa-solid fa-clock"></i><span>Horarios</span></a></li>
            <li><a href="perfil.php"><i class="fa-solid fa-book-bookmark"></i><span>Administrar reservaciones</span></a></li>
        <?php endif; ?>

        <?php if ($tipo == 3): ?>
            <li>
                <a href="edit_user.php"
                class="<?= ($currentPage === 'edit_user.php' ? 'active' : '') ?>">
                    <i class="fa-solid fa-user-pen"></i>
                    <span>Modificar usuarios</span>
                </a>
            </li>
            <li>
                <a href="usuarios.php"
                class="<?= ($currentPage === 'usuarios.php' ? 'active' : '') ?>">
                    <i class="fa-solid fa-user-gear"></i>
                    <span>Administrar usuarios</span>
                </a>
            </li>
        <?php endif; ?>

    </ul>


    <div class="logout">
        <a href="/cubi/auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i><span>Cerrar sesión</span></a>
    </div>


</div>
</body>
</html>

