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
?>

<style>
  ul li:nth-child(2) .activo{
    background: rgb(11, 150, 214) !important;
  }
</style>

<!-- primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<!-- inicio del contenido principal -->
<div class="page-content">

    <h4 class="text-center text-secondary">REGISTRO DE USUARIOS</h4>

    <?php
    include '../modelo/conexion.php';
   include "../controlador/controlador_registrar_usuario.php"
    ?>

<div class="row">
   <form action="" method="POST">
     <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
        <input type="text" placeholder="Nombre" class="input input__text" name="txtnombre">
</div>
<div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
        <input type="text" placeholder="Apellido" class="input input__text" name="txtapellido">
</div>
<div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
        <input type="text" placeholder="Usuario" class="input input__text" name="txtusuario">
</div>
<div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
        <input type="password" placeholder="Contraseña" class="input input__text" name="txtpassword">
</div>
<div class="text-right p-2">
   <a href="usuario.php" class="btn btn-secondary btn-rounded">Atrás</a>
   <button type="submit" value="ok" name="btnregistrar" class="btn btn-primary btn-rounded">Registrar</button>
</div>
</form>
</div>

</div>
</div>
<!-- fin del contenido principal -->


<!-- por ultimo se carga el footer -->
<?php require('./layout/footer.php'); ?>
<script>
window.addEventListener("pageshow", function(event) {
  if (event.persisted) {
    window.location.reload();
  }
});
</script>