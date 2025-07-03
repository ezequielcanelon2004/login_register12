<?php
// Eliminado session_start() automático. Solo las páginas protegidas deben iniciar sesión.
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, user-scalable=no" name="viewport">
    <title>UNIDAD EDUCATIVA "JACINTO CONVIT"</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link href="../public/bootstrap5/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/fontawesome/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8b02b9b95f.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Montserrat', 'Poppins', 'Roboto', Arial, sans-serif;
            background: rgb(155, 197, 236); /* Fondo celeste */
            margin: 0;
        }
        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 56px;
            background: #fff; /* Barra superior blanca */
            color: #222;
            display: flex;
            align-items: center;
            z-index: 300;
            padding: 0 20px;
            font-family: 'Montserrat', 'Poppins', 'Roboto', Arial, sans-serif;
        }
        .topbar .toggle-btn {
            background: none;
            border: none;
            color: #222;
            font-size: 1.7rem;
            margin-right: 20px;
            cursor: pointer;
            font-family: 'Montserrat', 'Poppins', 'Roboto', Arial, sans-serif;
        }
        .topbar .logo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff;
            object-fit: cover;
            margin-right: 12px;
        }
        .topbar .title {
            font-weight: bold;
            font-size: 1.1rem;
            letter-spacing: 1px;
            color: #222;
            font-family: 'Montserrat', 'Poppins', 'Roboto', Arial, sans-serif;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 56px;
            width: 240px;
            height: calc(100vh - 56px);
            background: #fff; /* Sidebar blanco */
            color: #222;
            z-index: 200;
            transition: width 0.3s;
            overflow-y: auto;
            overflow-x: hidden;
            font-family: 'Montserrat', 'Poppins', 'Roboto', Arial, sans-serif;
            box-shadow: 2px 0 8px rgba(0,0,0,0.04);
        }
        body.sidebar-closed .sidebar {
            width: 0;
        }
        .sidebar header {
            display: none;
        }
        .menu-bar {
            padding: 20px 0;
        }
        .menu-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .menu-links .nav-link {
            margin-bottom: 10px;
        }
        .menu-links .nav-link a {
            display: flex;
            align-items: center;
            color: #222 !important;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background 0.2s;
            font-weight: 500;
            gap: 10px;
            font-family: 'Montserrat', 'Poppins', 'Roboto', Arial, sans-serif;
        }
        .menu-links .nav-link a:hover, .menu-links .nav-link a.active {
            background: #0d6efd;
            color: #fff;
        }
        .menu-links .icon {
            font-size: 1.3rem;
            margin-right: 16px;
            min-width: 24px;
            text-align: center;
        }
        .sidebar .sidebar-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin: 18px 0 8px 0;
            color: #222 !important;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Montserrat', 'Poppins', 'Roboto', Arial, sans-serif;
        }
        body.sidebar-closed .nav-text {
            display: none;
        }
        .bottom-content {
            position: absolute;
            bottom: 20px;
            width: 100%;
            display: flex;
            justify-content: center;
            transition: bottom 0.3s;
        }
        @media (max-height: 700px) {
            .bottom-content {
                position: static;
                margin-top: 32px;
            }
        }
        .bottom-content li {
            width: 90%;
        }
        .bottom-content li a {
            display: flex; /* Asegura que el contenido esté alineado */
            align-items: center; /* Centra el contenido verticalmente */
            justify-content: center; /* Centra el contenido horizontalmente */
            background: #2563eb; /* Fondo azul similar al entorno */
            color: #fff; /* Texto blanco */
            padding: 10px 20px; /* Espaciado interno */
            border-radius: 8px; /* Bordes redondeados */
            font-weight: 500; /* Texto semi-negrita */
            text-decoration: none; /* Elimina el subrayado */
            width: 90%; /* Ajusta el ancho del botón */
            margin: 10px auto; /* Centrado horizontal */
            box-shadow: 0 2px 8px rgba(37,99,235,0.08); /* Sombra ligera */
            transition: background-color 0.3s ease, box-shadow 0.3s ease; /* Animación */
        }
        .bottom-content li a:hover {
            background: #1e40af; /* Fondo más oscuro al pasar el cursor */
            box-shadow: 0 4px 16px rgba(30,64,175,0.12); /* Sombra más pronunciada */
        }
        .bottom-content .icon {
            font-size: 1.2rem;
            margin-right: 6px;
        }
        .main-content {
            margin-left: 240px;
            margin-top: 56px;
            padding: 30px 20px 20px 20px;
            transition: margin-left 0.3s;
        }
        body.sidebar-closed .main-content {
            margin-left: 0px;
        }
        @media (max-width: 900px) {
            .sidebar {
                width: 0;
            }
            body.sidebar-closed .sidebar {
                width: 0;
            }
            .main-content, body.sidebar-closed .main-content {
                margin-left: 0;
            }
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li {
            padding: 14px 24px;
            color: #222 !important;
            font-size: 1.08rem;
            display: flex;
            align-items: center;
            transition: background 0.2s;
            font-family: 'Montserrat', 'Poppins', 'Roboto', Arial, sans-serif;
        }
        .sidebar-menu li a {
            color: #222 !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            width: 100%;
            gap: 12px;
            font-family: 'Montserrat', 'Poppins', 'Roboto', Arial, sans-serif;
        }
        .sidebar-menu li a:hover {
            background: #2563eb;
            border-radius: 8px;
        }
        .sidebar-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-top: 18px;
            margin-bottom: 6px;
            color: #222 !important;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Montserrat', 'Poppins', 'Roboto', Arial, sans-serif;
        }
        .logout-btn-container {
            display: flex;
            justify-content: center;
            margin-top: 32px;
        }
        .logout-btn {
            background: #2563eb;
            color: #fff;
            padding: 12px 32px;
            border-radius: 24px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(37,99,235,0.08);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .logout-btn:hover {
            background: #1e40af;
            box-shadow: 0 4px 16px rgba(30,64,175,0.12);
        }
        .fas, .far, .fab {
            font-size: 1.1em;
            margin-right: 8px;
        }
        .sidebar-submenu {
            padding-left: 32px;
        }
        .fa-chevron-down.rotate {
            transform: rotate(180deg);
            transition: transform 0.2s;
        }
    </style>
</head>
<body class="sidebar-closed">
    <!-- Topbar -->
    <div class="topbar">
        <button class="toggle-btn" id="sidebarToggle" title="Mostrar/Ocultar menú">
            <i class="fas fa-bars"></i>
        </button>
        <img src="../img/logo.png" alt="logo" class="logo">
        <span class="title">UNIDAD EDUCATIVA "JACINTO CONVIT"</span>
    </div>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="menu-bar">
            <ul class="sidebar-menu">
                <li class="sidebar-title collapsible" data-bs-toggle="collapse" data-bs-target="#inicioMenu" aria-expanded="false" aria-controls="inicioMenu" style="cursor:pointer;"><i class="fas fa-home"></i> INICIO <i class="fas fa-chevron-down ms-auto"></i></li>
                <ul class="collapse sidebar-submenu" id="inicioMenu">
                    <li><a href="../vista/inicio.php"><i class="fas fa-house"></i> Inicio</a></li>
                    <!-- Puedes agregar más opciones aquí -->
                </ul>

                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li class="sidebar-title collapsible" data-bs-toggle="collapse" data-bs-target="#usuariosMenu" aria-expanded="false" aria-controls="usuariosMenu" style="cursor:pointer;"><i class="fas fa-users"></i> USUARIOS <i class="fas fa-chevron-down ms-auto"></i></li>
                <ul class="collapse sidebar-submenu" id="usuariosMenu">
                    <li><a href="../vista/usuario.php"><i class="fas fa-user"></i> Usuarios</a></li>
                    <!-- Puedes agregar más opciones aquí -->
                </ul>

                <li class="sidebar-title collapsible" data-bs-toggle="collapse" data-bs-target="#empleadosMenu" aria-expanded="false" aria-controls="empleadosMenu" style="cursor:pointer;"><i class="fas fa-user-tie"></i> EMPLEADOS <i class="fas fa-chevron-down ms-auto"></i></li>
                <ul class="collapse sidebar-submenu" id="empleadosMenu">
                    <li><a href="../vista/empleado.php"><i class="fas fa-id-badge"></i> Empleados</a></li>
                    <!-- Puedes agregar más opciones aquí -->
                </ul>
                <li class="sidebar-title collapsible" data-bs-toggle="collapse" data-bs-target="#bdMenu" aria-expanded="false" aria-controls="bdMenu" style="cursor:pointer;"><i class="fas fa-database"></i> BASE DE DATOS <i class="fas fa-chevron-down ms-auto"></i></li>
                <ul class="collapse sidebar-submenu" id="bdMenu">
                    <li>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalRestaurarBD"><i class="fas fa-upload"></i> Restaurar BD</a>
                    </li>
                    <li>
                        <a href="<?php echo '/login_register12/respaldo_bd.php'; ?>" target="_blank"><i class="fas fa-database"></i> Descargar respaldo BD</a>
                    </li>
                </ul>
                <?php endif; ?>
            </ul>
        </div>
        <div class="bottom-content">
        <script src="https://kit.fontawesome.com/8b02b9b95f.js" crossorigin="anonymous"></script>
            <ul>
                <li><a href="../index.php" class="logout-btn">Cerrar sesión</a></li>
            </ul>
        </div>
    </nav>
    <!-- Contenido principal -->
    <div class="main-content">
        
       
    </div>
    <!-- Modal Restaurar BD (solo admin) -->
<div class="modal fade" id="modalRestaurarBD" tabindex="-1" aria-labelledby="modalRestaurarBDLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-gradient bg-warning text-dark rounded-top-4 border-0">
        <h5 class="modal-title fw-bold" id="modalRestaurarBDLabel">
          <i class="fa-solid fa-database me-2"></i>Restaurar Base de Datos
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-light">
        <form action="../restaurar_bd.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label fw-semibold mb-1">Selecciona archivo .sql</label>
            <input type="file" name="sql_file" accept=".sql" class="form-control form-control-lg rounded-3" required>
          </div>
          <button type="submit" class="btn btn-warning w-100 rounded-pill mt-2">
            <i class="fa-solid fa-database me-1"></i> Restaurar
          </button>
        </form>
        <div class="alert alert-warning mt-3 mb-0" style="font-size:0.95rem">
          <i class="fa-solid fa-triangle-exclamation me-1"></i> Esta acción reemplazará los datos actuales. ¡Haz un respaldo antes!
        </div>
      </div>
    </div>
  </div>
</div>
    <script src="../public/bootstrap5/js/bootstrap.min.js"></script>
    <script>
        // Sidebar desplegable
        document.getElementById('sidebarToggle').onclick = function() {
            document.body.classList.toggle('sidebar-closed');
            // Al retraer, cerrar todos los submenús y flechas
            if(document.body.classList.contains('sidebar-closed')){
                document.querySelectorAll('.sidebar-submenu').forEach(function(sub){
                    sub.classList.remove('show');
                });
                document.querySelectorAll('.fa-chevron-down').forEach(function(icon){
                    icon.classList.remove('rotate');
                });
            }
        };
        // Desplegar/cerrar solo un menú a la vez
        document.querySelectorAll('.collapsible').forEach(function(title){
            title.addEventListener('click', function(e){
                // Evitar que se cierre el sidebar al hacer click en el título
                e.stopPropagation();
                var target = document.querySelector(this.getAttribute('data-bs-target'));
                var isOpen = target.classList.contains('show');
                // Cerrar todos
                document.querySelectorAll('.sidebar-submenu').forEach(function(sub){
                    sub.classList.remove('show');
                });
                document.querySelectorAll('.fa-chevron-down').forEach(function(icon){
                    icon.classList.remove('rotate');
                });
                // Abrir solo si no estaba abierto
                if(!isOpen && !document.body.classList.contains('sidebar-closed')){
                    target.classList.add('show');
                    var icon = this.querySelector('.fa-chevron-down');
                    if(icon) icon.classList.add('rotate');
                }
            });
        });
    </script>
</body>
</html>