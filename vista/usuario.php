<?php
   session_start();
   header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
   header('Cache-Control: post-check=0, pre-check=0', false);
   header('Pragma: no-cache');
   header('Expires: 0');
   if (empty($_SESSION['email'])) {
       header("Location: /login_register12/index.php");
       exit();
   }
?>

<style>
  ul li:nth-child(2) .activo{
    background: rgb(11, 150, 214) !important;
  }
  .page-content {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 32px 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    min-height: 80vh;
  }
  .table thead th { background: #e9ecef; }
  .btn-lg { font-size: 1.1rem; padding: 0.7rem 1.5rem; }
  /* Ocultar paginación nativa de DataTables */
  .dataTables_paginate { display: none !important; }
  /* Sidebar y botones */
  .sidebar {
    background: #212529;
    min-height: 100vh;
    color: #fff;
  }
  .sidebar .nav-link {
    color: #fff;
    font-weight: 500;
    border-radius: 8px;
    margin-bottom: 8px;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .sidebar .nav-link.active, .sidebar .nav-link:hover {
    background: #0d6efd;
    color: #fff;
  }
  .sidebar .nav-link i {
    font-size: 1.2rem;
    min-width: 22px;
    text-align: center;
  }
  .sidebar .sidebar-title {
    font-size: 1.1rem;
    font-weight: bold;
    margin: 18px 0 8px 0;
    color: #adb5bd;
    letter-spacing: 1px;
    text-transform: uppercase;
  }
  /* Botones principales */
  .btn-success, .btn-primary, .btn-danger, .btn-warning {
    border-radius: 2rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    transition: background 0.2s, color 0.2s;
  }
  .btn-success:hover, .btn-primary:hover, .btn-danger:hover, .btn-warning:hover {
    filter: brightness(1.1);
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
  }
  /* Título de página */
  .page-title {
    font-size: 2rem;
    font-weight: bold;
    color: #0d6efd;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 12px;
  }
</style>

<!-- primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<!-- inicio del contenido principal -->
<script src="https://kit.fontawesome.com/8b02b9b95f.js" crossorigin="anonymous"></script>
<div class="page-content">
    <div class="container" style="max-width: 900px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="page-title mb-0"><i class="fa-solid fa-users"></i> Lista de Usuarios</h3>
            <button type="button" class="btn btn-success btn-lg shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalRegistrarUsuario">
              <i class="fa-solid fa-plus"></i> Registrar
            </button>
        </div>
        <?php
        include "../modelo/conexion.php";
        include "../controlador/controlador_modificar_usuario.php";
        include "../controlador/controlador_eliminar_usuario.php";
        include "../controlador/controlador_registrar_usuario.php";

        // Acción de borrado (eliminación suave con recarga suave)
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $conexion->query("DELETE FROM users WHERE id = $id");
            echo "<script>setTimeout(function(){ location.href='usuario.php'; }, 600);</script>";
        }

        // Acción de modificación (solo usuario y email)
        if (!empty($_POST['btnmodificar']) && !empty($_POST['txtid']) && !empty($_POST['txtusuario']) && !empty($_POST['txtemail'])) {
            $id = intval($_POST['txtid']);
            $usuario = $_POST['txtusuario'];
            $email = $_POST['txtemail'];
            $update = $conexion->query("UPDATE users SET usuario='$usuario', email='$email' WHERE id=$id");
            if ($update === true) {
                echo "<script>setTimeout(function(){ location.href='usuario.php'; }, 600);</script>";
            } else {
                ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'INCORRECTO',
                        text: 'Error al modificar usuario. Detalles: <?= htmlspecialchars($conexion->error, ENT_QUOTES, 'UTF-8') ?>',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    });
                </script>
                <?php
            }
        }

        $sql = $conexion->query(" SELECT * from users ");
        ?>
        <div class="table-responsive rounded shadow-sm">
            <table class="table table-bordered table-hover bg-white" id="example">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">USUARIO</th>
                        <th scope="col">EMAIL</th>
                        <th scope="col">NIVEL DE ACCESO</th>
                        <th class="text-center" style="width: 160px;"></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while ($datos = $sql->fetch_object()) { ?>
                    <tr>
                        <td><?= $datos->usuario ?></td>
                        <td><?= $datos->email ?></td>
                        <td>
                            <?php if(isset($datos->role) && $datos->role == 'admin'): ?>
                                <span class="badge bg-danger">Administrador</span>
                            <?php else: ?>
                                <span class="badge bg-primary">Usuario</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-sm mx-1 shadow-sm" title="Modificar" data-bs-toggle="modal" data-bs-target="#modalModificarUsuario<?= $datos->id ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <a href="usuario.php?id=<?= $datos->id ?>" class="btn btn-danger btn-sm mx-1 shadow-sm btn-eliminar" title="Eliminar"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <!-- Modal Modificar Usuario -->
                    <div class="modal fade" id="modalModificarUsuario<?= $datos->id ?>" tabindex="-1" aria-labelledby="modalModificarUsuarioLabel<?= $datos->id ?>" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                          <div class="modal-header bg-gradient bg-primary text-white rounded-top-4 border-0">
                            <h5 class="modal-title fw-bold" id="modalModificarUsuarioLabel<?= $datos->id ?>">
                              <i class="fa-solid fa-user-pen me-2"></i>Modificar Usuario
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body p-4 bg-light">
                            <form action="" method="POST" autocomplete="off">
                              <input type="hidden" name="txtid" value="<?= $datos->id ?>">
                              <div class="mb-3">
                                <label class="form-label fw-semibold">Usuario</label>
                                <div class="input-group">
                                  <span class="input-group-text bg-white border-end-0"><i class="fa fa-user"></i></span>
                                  <input type="text" class="form-control form-control-lg rounded-3 border-start-0" name="txtusuario" value="<?= $datos->usuario ?>" required>
                                </div>
                              </div>
                              <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                  <span class="input-group-text bg-white border-end-0"><i class="fa fa-envelope"></i></span>
                                  <input type="email" class="form-control form-control-lg rounded-3 border-start-0" name="txtemail" value="<?= $datos->email ?>" required>
                                </div>
                              </div>
                              <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">
                                  <i class="fa fa-times me-1"></i> Cancelar
                                </button>
                                <button type="submit" value="ok" name="btnmodificar" class="btn btn-primary px-4 rounded-pill shadow-sm">
                                  <i class="fa fa-check me-1"></i> Guardar
                                </button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php }
                ?>
                </tbody>
            </table>
        </div>
        <nav aria-label="Paginación de usuarios" class="mt-4">
          <ul class="pagination justify-content-center">
            <li class="page-item">
              <a class="page-link" href="#" id="btnAnterior"><i class="fa fa-arrow-left"></i> Anterior</a>
            </li>
            <li class="page-item disabled"><span class="page-link">1</span></li>
            <li class="page-item">
              <a class="page-link" href="#" id="btnSiguiente">Siguiente <i class="fa fa-arrow-right"></i></a>
            </li>
          </ul>
        </nav>
    </div>
</div>
<!-- Modal Registrar Usuario -->
<div class="modal fade" id="modalRegistrarUsuario" tabindex="-1" aria-labelledby="modalRegistrarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-gradient bg-success text-white rounded-top-4 border-0">
        <h5 class="modal-title fw-bold" id="modalRegistrarUsuarioLabel">
          <i class="fa-solid fa-user-plus me-2"></i>Registrar Usuario
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-light">
        <form action="" method="POST" autocomplete="off">
          <div class="mb-3">
            <label class="form-label fw-semibold">Usuario</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0"><i class="fa fa-user"></i></span>
              <input type="text" class="form-control form-control-lg rounded-3 border-start-0" name="txtusuario" placeholder="Nombre y Apellido o seudónimo">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0"><i class="fa fa-envelope"></i></span>
              <input type="email" class="form-control form-control-lg rounded-3 border-start-0" name="txtemail" placeholder="ejemplo@email.com">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Contraseña</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0"><i class="fa fa-lock"></i></span>
              <input type="password" class="form-control form-control-lg rounded-3 border-start-0" name="txtpassword" placeholder="Mínimo 6 caracteres">
            </div>
          </div>
          <input type="hidden" name="txtrol" value="user">
          <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">
              <i class="fa fa-times me-1"></i> Cancelar
            </button>
            <button type="submit" value="ok" name="btnregistrar" class="btn btn-success px-4 rounded-pill shadow-sm">
              <i class="fa fa-check me-1"></i> Registrar
            </button>
          </div>
        </form>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            const modalFormUsuario = document.querySelector('#modalRegistrarUsuario form');
            modalFormUsuario.addEventListener('submit', function(e) {
              const usuario = modalFormUsuario.querySelector('[name="txtusuario"]').value;
              const email = modalFormUsuario.querySelector('[name="txtemail"]').value;
              const password = modalFormUsuario.querySelector('[name="txtpassword"]').value;

              if (!usuario || !email || !password) {
                e.preventDefault();
                alert('Por favor, complete todos los campos antes de registrar el usuario.');
              }
            });
          });
        </script>
      </div>
    </div>
  </div>
</div>
<!-- fin del contenido principal -->

<!-- por ultimo se carga el footer -->
<?php require('./layout/footer.php'); ?>
<script src="/login_register12/public/sweet/js/sweetalert2.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Estilos para los botones de acción
    document.querySelectorAll('.btn-warning').forEach(btn => {
        btn.classList.add('shadow-sm');
    });
    document.querySelectorAll('.btn-danger').forEach(btn => {
        btn.classList.add('shadow-sm');
    });

    // Confirmación de eliminación
    document.querySelectorAll('a.btn-eliminar').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = btn.getAttribute('href');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });

    // Validaciones en el formulario de registro
    document.querySelector('form[action=""]').addEventListener('submit', function(e) {
        e.preventDefault();

        const usuario = this.querySelector('input[name="txtusuario"]').value.trim();
        const email = this.querySelector('input[name="txtemail"]').value.trim();
        const password = this.querySelector('input[name="txtpassword"]').value.trim();

        if (!usuario) {
            Swal.fire({
                icon: 'error',
                title: 'Campo vacío',
                text: 'El campo Usuario es obligatorio.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        if (!email) {
            Swal.fire({
                icon: 'error',
                title: 'Campo vacío',
                text: 'El campo Email es obligatorio.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        if (!password || password.length < 6) {
            Swal.fire({
                icon: 'error',
                title: 'Contraseña inválida',
                text: 'La contraseña debe tener al menos 6 caracteres.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        this.submit();
    });
});

window.addEventListener('pageshow', function(event) {
  if (event.persisted) {
    window.location.reload();
  }
});
</script>