<?php
   session_start();
   if (empty($_SESSION['email']) and empty($_SESSION['password'])) {
       header("Location: /login_register/index.php");
   }

?>

<style>
  ul li:nth-child(1) .activo{
    background: rgb(11, 150, 214) !important;
  }
</style>

<!-- primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<!-- inicio del contenido principal -->
<div class="page-content">

    <h4 class="text-center text-secondary">ASISTENCIAS DE EMPLEADOS</h4>

   <?php
   include "../modelo/conexion.php";
   $sql=$conexion->query(" select * from empleado ");
   ?>

  <form action="fpdf/ReporteAsistenciaEmpleado.php">
      <input required type="date" name="txtfechainicio" class="input input__text mb-2" placeholder="Fecha inicio">
      <input required type="date" name="txtfechafinal" class="input input__text mb-2" placeholder="Fecha final">
      <select required name="txtempleado" class="input input__select mb-2">
          <option value="">Seleccione un empleado</option>
          <?php
          while ($datos=$sql->fetch_object()) { ?>
            <option value="<?= $datos->id_empleado ?>"><?= $datos->nombre . " " . $datos->apellido ?></option>
          <?php }
          ?>
      </select>
      <button type="submit" name="btngenerar" class="btn btn-primary w-100 p-3">Generar Reporte</button>
  </form>
 

</div>
</div>
<!-- fin del contenido principal -->


<!-- por ultimo se carga el footer -->
<?php require('./layout/footer.php'); ?>