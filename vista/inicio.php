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
   // Mostrar SweetAlert si hay login_success
   if (!empty($_SESSION['login_success'])) {
      $role = $_SESSION['login_success']['role'] === 'admin' ? 'Administrador' : 'Usuario';
      $usuario = $_SESSION['login_success']['usuario'];
      echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">';
      echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
      echo '<script>Swal.fire({
        title: "Bienvenido ' . $usuario . '",
        text: "Nivel de acceso: ' . $role . '",
        icon: "success",
        showConfirmButton: false,
        timer: 1800
      });</script>';
      unset($_SESSION['login_success']);
   }
?>

<style>
  ul li:nth-child(1) .activo{
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
</style>

<?php require('./layout/topbar.php'); ?>
<?php require('./layout/sidebar.php'); ?>

<script src="https://kit.fontawesome.com/8b02b9b95f.js" crossorigin="anonymous"></script>
<div class="container mt-4">
  <div class="page-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="page-title mb-0"><i class="fa-solid fa-calendar-check"></i> Asistencias de Empleados</h3>
      <div>
        <a href="fpdf/ReporteAsistencia.php" target="_blank" class="btn btn-success btn-lg shadow-sm mx-1">
          <i class="fas fa-file-pdf"></i> Generar reportes
        </a>
        <button type="button" class="btn btn-primary btn-lg shadow-sm mx-1" data-bs-toggle="modal" data-bs-target="#modalReportes">
          <i class="fas fa-plus"></i> Más reportes
        </button>
      </div>
    </div>
    <?php
    include "../modelo/conexion.php";
    include "../controlador/Controlador_eliminar_asistencia.php";
    if (isset($_SESSION['mensaje'])) {
        $msg = $_SESSION['mensaje'];
        $alertType = (stripos($msg, 'error') !== false || stripos($msg, 'Error') !== false) ? 'danger' : 'success';
        echo '<div class="alert alert-' . $alertType . '" style="white-space:pre-wrap;word-break:break-all;max-width:100%;overflow:auto;">' . $msg . '</div>';
        unset($_SESSION['mensaje']);
    }
    $sql=$conexion->query(" SELECT
      asistencia.id_asistencia,
      asistencia.id_empleado,
      asistencia.entrada,
      asistencia.salida,
      empleado.id_empleado,
      empleado.nombre as 'nom_empleado',
      empleado.apellido,
      empleado.dni,
      empleado.cargo,
      cargo.id_cargo,
      cargo.nombre as 'nom_cargo'
      FROM
      asistencia
      INNER JOIN empleado ON asistencia.id_empleado = empleado.id_empleado
      INNER JOIN cargo ON empleado.cargo = cargo.id_cargo ");
    ?>
    <div class="table-responsive rounded shadow-sm">
      <table class="table table-bordered table-hover bg-white" id="example">
        <thead class="thead-light">
          <tr>
            <th scope="col">EMPLEADO</th>
            <th scope="col">CÉDULA</th>
            <th scope="col">CARGO</th>
            <th scope="col">ENTRADA</th>
            <th scope="col">SALIDA</th>
            <th scope="col" class="text-center" style="width: 120px;">ACCIONES</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($datos = $sql->fetch_object()) { ?>
            <tr>
              <td><?= $datos->nom_empleado . " " . $datos->apellido ?></td>
              <td><?= $datos->dni ?></td>
              <td><?= $datos->nom_cargo ?></td>
              <td><?= date('d/m/Y H:i', strtotime($datos->entrada)) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($datos->salida)) ?></td>
              <td class="text-center">
                <a href="inicio.php?id=<?=$datos->id_asistencia?>" class="btn btn-danger btn-sm mx-1 shadow-sm btn-eliminar" title="Eliminar">
                  <i class="fa-solid fa-trash"></i>
                </a>
              </td>
            </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
    <nav aria-label="Paginación de asistencias" class="mt-4">
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
<!-- Modal Más Reportes -->
<div class="modal fade" id="modalReportes" tabindex="-1" aria-labelledby="modalReportesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-gradient bg-primary text-white rounded-top-4 border-0">
        <h5 class="modal-title fw-bold" id="modalReportesLabel">
          <i class="fa-solid fa-file-alt me-2"></i>Generar reportes de asistencia
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-light">
        <form action="fpdf/ReporteAsistenciaEmpleado.php" method="GET" target="_blank" id="modalForm">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold mb-1"><i class="fa-solid fa-calendar-alt text-info me-1"></i>Fecha Inicio</label>
              <input type="date" name="txtfechainicio" class="form-control form-control-lg rounded-3">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold mb-1"><i class="fa-solid fa-calendar-alt text-info me-1"></i>Fecha Final</label>
              <input type="date" name="txtfechafinal" class="form-control form-control-lg rounded-3">
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold mb-1"><i class="fa-solid fa-clock text-info me-1"></i>Hora Inicio</label>
              <input type="time" name="txthorainicio" class="form-control form-control-lg rounded-3">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold mb-1"><i class="fa-solid fa-clock text-info me-1"></i>Hora Final</label>
              <input type="time" name="txthorafinal" class="form-control form-control-lg rounded-3">
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-12">
              <label class="form-label fw-semibold mb-1"><i class="fa-solid fa-users text-info me-1"></i>Seleccionar Empleado</label>
              <select name="txtempleado" class="form-select form-select-lg rounded-3">
                <option value="" disabled selected>Seleccione un empleado</option>
                <?php
                $empleados = $conexion->query("SELECT id_empleado, nombre, apellido FROM empleado");
                while($emp = $empleados->fetch_object()) {
                  echo '<option value="'.$emp->id_empleado.'">'.htmlspecialchars($emp->nombre.' '.$emp->apellido).'</option>';
                }
                ?>
              </select>
            </div>
          </div>
          <button type="submit" class="btn btn-primary w-100 rounded-pill mt-2">
            <i class="fa-solid fa-file-pdf me-1"></i> Generar Reporte
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="/login_register12/public/sweet/js/sweetalert2.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
  document.addEventListener('DOMContentLoaded', function() {
    const modalForm = document.querySelector('#modalForm');
    modalForm.addEventListener('submit', function(e) {
      const fechaInicio = modalForm.querySelector('[name="txtfechainicio"]').value;
      const fechaFinal = modalForm.querySelector('[name="txtfechafinal"]').value;
      const horaInicio = modalForm.querySelector('[name="txthorainicio"]').value;
      const horaFinal = modalForm.querySelector('[name="txthorafinal"]').value;
      const empleado = modalForm.querySelector('[name="txtempleado"]').value;

      if (!fechaInicio || !fechaFinal || !horaInicio || !horaFinal || !empleado) {
        e.preventDefault();
        alert('Por favor, complete todos los campos antes de generar el reporte.');
      }
    });
  });
</script>
<script>
// Deshabilitar validaciones nativas
const inputs = document.querySelectorAll('#modalReportes input, #modalReportes select');
inputs.forEach(input => {
  input.setAttribute('novalidate', true);
});
</script>
<script>
window.addEventListener("pageshow", function(event) {
  if (event.persisted) {
    window.location.reload();
  }
});
</script>
<?php require('./layout/footer.php'); ?>