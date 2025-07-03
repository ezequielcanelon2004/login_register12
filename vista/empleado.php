<?php
   session_start();
   // Cabeceras para evitar caché
   header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
   header('Cache-Control: post-check=0, pre-check=0', false);
   header('Pragma: no-cache');
   header('Expires: 0');
   if (empty($_SESSION['email'])) {
       header("Location: /login_register12/index.php");
       exit();
   }
   include "../modelo/conexion.php";
   include "../controlador/controlador_registrar_empleado.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
  ul li:nth-child(3) .activo{
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
  .dataTables_paginate { display: none !important; }
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
  .page-title {
    font-size: 2rem;
    font-weight: bold;
    color: #0d6efd;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  body, .page-content, .form-label, .form-control, .table, .sidebar, .btn, .page-title {
    font-family: 'Poppins', 'Montserrat', Arial, sans-serif !important;
  }
  .page-title, .modal-title, .btn-lg, .btn, .form-label {
    font-family: 'Montserrat', 'Poppins', Arial, sans-serif !important;
    letter-spacing: 0.5px;
  }
  .page-title {
    font-weight: 700;
    font-size: 2.1rem;
  }
  .btn-lg, .btn {
    font-weight: 600;
    font-size: 1.08rem;
    border-radius: 2rem;
  }
</style>

<!-- primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<!-- inicio del contenido principal -->
<script src="https://kit.fontawesome.com/8b02b9b95f.js" crossorigin="anonymous"></script>
<div class="page-content">
    <div class="container" style="max-width: 950px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="page-title mb-0"><i class="fa-solid fa-users"></i> Lista de Empleados</h3>
            <button type="button" class="btn btn-success btn-lg shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalRegistrarEmpleado">
              <i class="fa-solid fa-plus"></i> Registrar
            </button>
        </div>
        <form class="row g-3 mb-3 align-items-center justify-content-end">
            <div class="col-auto">
                <label for="cantidadRegistros" class="col-form-label">Mostrar</label>
            </div>
            <div class="col-auto">
                <select class="form-select form-select-sm" id="cantidadRegistros">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </form>
        <div class="table-responsive rounded shadow-sm">
            <table class="table table-bordered table-hover bg-white" id="example">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">NOMBRE</th>
                        <th scope="col">APELLIDO</th>
                        <th scope="col">CÉDULA</th>
                        <th scope="col">CARGO</th>
                        <th class="text-center" style="width: 160px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
include "../modelo/conexion.php";
include "../controlador/controlador_modificar_empleado.php";
include "../controlador/controlador_eliminar_empleado.php";

   $sql = $conexion->query(" SELECT
   empleado.id_empleado,
   empleado.nombre,
   empleado.apellido,
   empleado.dni,
   empleado.cargo,
   cargo.nombre as 'nom_cargo'
   FROM
   empleado
   INNER JOIN cargo ON empleado.cargo = cargo.id_cargo
    ");

                    while ($datos = $sql->fetch_object()) { ?>
                        <tr>
                            <td><?= $datos->nombre ?></td>
                            <td><?= $datos->apellido ?></td>
                            <td><?= $datos->dni ?></td>
                            <td><?= $datos->nom_cargo ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning btn-sm mx-1 shadow-sm" title="Modificar" data-bs-toggle="modal" data-bs-target="#modalModificarEmpleado<?= $datos->id_empleado ?>">
                                  <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <a href="empleado.php?id=<?=$datos->id_empleado ?>" class="btn btn-danger btn-sm mx-1 shadow-sm btn-eliminar" title="Eliminar"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <!-- Modal Modificar Empleado -->
                        <div class="modal fade" id="modalModificarEmpleado<?= $datos->id_empleado ?>" tabindex="-1" aria-labelledby="modalModificarEmpleadoLabel<?= $datos->id_empleado ?>" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                              <div class="modal-header bg-gradient bg-primary text-white rounded-top-4 border-0">
                                <h5 class="modal-title fw-bold" id="modalModificarEmpleadoLabel<?= $datos->id_empleado ?>">
                                  <i class="fa-solid fa-user-pen me-2"></i>Modificar Empleado
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body p-4 bg-light">
                                <form action="" method="POST" autocomplete="off">
                                  <input type="hidden" name="txtid" value="<?= $datos->id_empleado ?>">
                                  <div class="mb-3">
                                    <label class="form-label fw-semibold">Nombre</label>
                                    <input type="text" class="form-control form-control-lg rounded-3" name="txtnombre" value="<?= $datos->nombre ?>">
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label fw-semibold">Apellido</label>
                                    <input type="text" class="form-control form-control-lg rounded-3" name="txtapellido" value="<?= $datos->apellido ?>">
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label fw-semibold">Cédula</label>
                                    <input type="text" class="form-control form-control-lg rounded-3" name="txtdni" value="<?= $datos->dni ?>">
                                  </div>
                                  <div class="mb-4">
                                    <label class="form-label fw-semibold">Cargo</label>
                                    <select name="txtcargo" class="form-control form-control-lg rounded-3">
                                      <?php
                                      $sql2 = $conexion->query(" select * from cargo ");
                                      while ($datos2 = $sql2->fetch_object()) { ?>
                                          <option <?= $datos->cargo==$datos2->id_cargo ? 'selected' : '' ?> value="<?= $datos2->id_cargo ?>"><?= $datos2->nombre ?></option>
                                      <?php }
                                      ?>
                                    </select>
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
        <nav aria-label="Paginación de empleados" class="mt-4">
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
<!-- Modal Registrar Empleado -->
<div class="modal fade" id="modalRegistrarEmpleado" tabindex="-1" aria-labelledby="modalRegistrarEmpleadoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-gradient bg-success text-white rounded-top-4 border-0">
        <h5 class="modal-title fw-bold" id="modalRegistrarEmpleadoLabel">
          <i class="fa-solid fa-user-plus me-2"></i>Registrar Empleado
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-light">
        <form action="" method="POST" autocomplete="off">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nombre</label>
            <input type="text" class="form-control form-control-lg rounded-3" name="txtnombre" placeholder="Nombre del empleado">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Apellido</label>
            <input type="text" class="form-control form-control-lg rounded-3" name="txtapellido" placeholder="Apellido del empleado">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Cédula</label>
            <input type="text" class="form-control form-control-lg rounded-3" name="txtdni" placeholder="Cédula">
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Cargo</label>
            <select name="txtcargo" class="form-control form-control-lg rounded-3">
              <?php
              $sql2 = $conexion->query(" select * from cargo ");
              while ($datos2 = $sql2->fetch_object()) { ?>
                  <option value="<?= $datos2->id_cargo ?>"><?= $datos2->nombre ?></option>
              <?php }
              ?>
            </select>
          </div>
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
            const modalFormEmpleado = document.querySelector('#modalRegistrarEmpleado form');
            modalFormEmpleado.addEventListener('submit', function(e) {
              const nombre = modalFormEmpleado.querySelector('[name="txtnombre"]').value;
              const apellido = modalFormEmpleado.querySelector('[name="txtapellido"]').value;
              const dni = modalFormEmpleado.querySelector('[name="txtdni"]').value;

              if (!nombre || !apellido || !dni) {
                e.preventDefault();
                alert('Por favor, complete todos los campos antes de registrar el empleado.');
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
    document.querySelectorAll('.btn-warning').forEach(btn => {
        btn.classList.add('shadow-sm');
    });
    document.querySelectorAll('.btn-danger').forEach(btn => {
        btn.classList.add('shadow-sm');
    });
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
});
</script>
<script>
window.addEventListener("pageshow", function(event) {
  if (event.persisted) {
    window.location.reload();
  }
});
</script>