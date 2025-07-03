<?php

session_start();

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? '',
    'security' => $_SESSION['security_error'] ?? '',
    'correo' => $_SESSION['correo_error'] ?? ''
];
$success = $_SESSION['security_success'] ?? '';
$activeForm = 'login'; // Forzar siempre el login como formulario inicial
$activeTab = 'seguridad'; // Forzar que la pestaña activa sea la de seguridad
// Limpiar solo los mensajes después de usarlos
unset($_SESSION['login_error'], $_SESSION['register_error'], $_SESSION['security_error'], $_SESSION['correo_error'], $_SESSION['security_success']);

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro</title>
    <link rel="stylesheet" href="style.css"> <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="img/logo.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <script>
      // Mostrar el formulario activo al cargar la página
      document.addEventListener('DOMContentLoaded', function() {
        showForm('login-form'); // Siempre mostrar el login al cargar
      });
    </script>
    <div class="container">
      <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form" style="display:none;">
          <form action="login_register.php" method="post">
              <h2>Ingresar</h2>
              <input type="email" name="email" placeholder="Correo" class="form-control mb-3" required>
              <input type="password" name="password" placeholder="Contraseña" class="form-control mb-3" required>
              <button type="submit" name="login" class="btn btn-primary w-100">Iniciar Sesión</button>
              <p class="mt-3">¿No tienes una cuenta?<a href="#" onclick="showForm('register-form')"> Registrarse</a></p>
              <p class="mt-2"><a href="#" onclick="showForm('forgot-form')" style="color:#0d6efd; text-decoration:underline;">¿Olvidaste tu contraseña?</a></p>
          </form>
      </div>


      <div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form" style="display:none;">
        <form action="login_register.php" method="post">
            <h2>Registro</h2>
            <?= showError($errors['register']); ?>
            <input type="text" name="usuario" placeholder="Usuario" class="form-control mb-3" required>
            <input type="email" name="email" placeholder="Correo" class="form-control mb-3" required>
            <input type="password" name="password" placeholder="Contraseña" class="form-control mb-3" required>
            <select name="role" class="form-control mb-3" required>
                <option value="user">Usuario</option>
                <option value="admin">Administrador</option>
            </select>
            <button type="submit" name="register" class="btn btn-primary w-100">Registrarse</button>
            <p class="mt-3">¿Ya tienes una cuenta?<a href="#" onclick="showForm('login-form')"> Iniciar Sesión</a></p>
        </form>
    </div>

    <div class="form-box" id="forgot-form" style="display:none; max-width:400px; margin:auto;">
        <div class="tabs mb-3">
            <button type="button" class="tab-btn active" onclick="showTab('seguridad')">Preguntas de seguridad</button>
        </div>
        <div id="tab-seguridad" class="tab-content active">
            <?php if (!empty($errors['security'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors['security'] ?>
                </div>
            <?php endif; ?>
            <form action="process_security_question.php" method="post">
                <h2 class="mb-3" style="font-size:1.3rem;">Recuperar con Pregunta de Seguridad</h2>
                <input type="email" name="email" placeholder="Correo electrónico" class="form-control mb-3" required>
                <select name="pregunta" class="form-select mb-3" required>
                  <option value="">Selecciona tu pregunta de seguridad...</option>
                  <option value="color">¿Cuál es tu color favorito?</option>
                  <option value="mascota">¿Nombre de tu primera mascota?</option>
                  <option value="ciudad">¿Ciudad donde naciste?</option>
                  <option value="madre">¿Segundo nombre de tu madre?</option>
                </select>
                <input type="text" name="respuesta" placeholder="Respuesta a tu pregunta de seguridad" class="form-control mb-3" required>
                <button type="submit" class="btn btn-primary w-100">Verificar y Recuperar</button>
            </form>
            <div class="text-center mt-3">
                <a href="#" onclick="showForm('login-form'); return false;" class="text-decoration-underline">Volver al inicio de sesión</a>
            </div>
            <button type="button" class="btn btn-link mt-3 w-100" data-bs-toggle="modal" data-bs-target="#modalPregunta">
                <i class="bx bx-cog"></i> Configurar pregunta de seguridad
            </button>
        </div>
    </div>

    <!-- Modal Bootstrap para configurar pregunta de seguridad -->
    <div class="modal fade" id="modalPregunta" tabindex="-1" aria-labelledby="modalPreguntaLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalPreguntaLabel"><i class="bx bx-shield-quarter me-2"></i>Configurar pregunta de seguridad</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <form action="process_set_security_question.php" method="post">
            <div class="modal-body">
              <?php if (!empty($success)): ?>
                <div class="alert alert-success" role="alert">
                  <?= $success ?>
                </div>
                <script>
                  setTimeout(function(){ window.location.href = 'index.php'; }, 2000);
                </script>
              <?php elseif (!empty($errors['security'])): ?>
                <div class="alert alert-danger" role="alert">
                  <?= $errors['security'] ?>
                </div>
              <?php endif; ?>
              <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Pregunta de seguridad</label>
                <select name="pregunta" class="form-select" required>
                  <option value="">Selecciona una pregunta...</option>
                  <option value="color">¿Cuál es tu color favorito?</option>
                  <option value="mascota">¿Nombre de tu primera mascota?</option>
                  <option value="ciudad">¿Ciudad donde naciste?</option>
                  <option value="madre">¿Segundo nombre de tu madre?</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Respuesta</label>
                <input type="text" name="respuesta" class="form-control" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <?php if (!empty($success) || !empty($errors['security'])): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('modalPregunta'));
        modal.show();
      });
    </script>
    <?php endif; ?>

    </div>

    <style>
    .tabs { display: flex; gap: 10px; margin-bottom: 20px; justify-content:center; }
    .tab-btn { padding: 8px 18px; border: none; border-radius: 20px; background: #e9ecef; color: #333; font-weight: 600; cursor: pointer; }
    .tab-btn.active { background: #0d6efd; color: #fff; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .form-box { max-width: 400px; margin: 30px auto; background: #fff; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 32px 24px; }
    @media (max-width: 500px) { .form-box { padding: 18px 5px; } }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function showTab(tab) {
        document.getElementById('tab-correo').classList.remove('active');
        document.getElementById('tab-seguridad').classList.remove('active');
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        if(tab === 'seguridad') {
            document.getElementById('tab-seguridad').classList.add('active');
            document.querySelectorAll('.tab-btn')[0].classList.add('active');
        }
    }
    function showForm(formId) {
        document.getElementById('login-form').style.display = 'none';
        document.getElementById('register-form').style.display = 'none';
        document.getElementById('forgot-form').style.display = 'none';
        document.getElementById(formId).style.display = 'block';
    }
    // Si se cierra el modal, mostrar el login por defecto
    var modalPregunta = document.getElementById('modalPregunta');
    if (modalPregunta) {
      modalPregunta.addEventListener('hidden.bs.modal', function () {
        showForm('login-form');
      });
    }
    </script>

</body>
</html>