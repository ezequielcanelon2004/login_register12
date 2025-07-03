<?php
// reset_password.php

// SQL para agregar campos de seguridad a la tabla usuario:
// ALTER TABLE usuario ADD COLUMN pregunta_seguridad VARCHAR(255) NULL, ADD COLUMN respuesta_seguridad VARCHAR(255) NULL;
//
// PHPMailer ya está integrado en process_forgot_password.php. Solo debes configurar:
// - $mail->Host = 'smtp.tu-servidor.com';
// - $mail->Username = 'tu-correo@dominio.com';
// - $mail->Password = 'tu-password';
// - $mail->setFrom('no-reply@tudominio.com', 'Soporte');
//
// Si usas Gmail, activa "acceso de apps menos seguras" o usa una contraseña de aplicación.
//
// El resto del flujo de recuperación y seguridad ya está implementado.

session_start();
require 'config.php';

// Paso 2: Mostrar formulario para nueva contraseña si verificado por pregunta de seguridad
if ((isset($_SESSION['reset_verified']) && $_SESSION['reset_verified']) && isset($_GET['step']) && $_GET['step'] === 'security') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nueva_password'])) {
        $email = $_SESSION['reset_email'] ?? '';
        $nueva = trim($_POST['nueva_password']);
        // Seguridad: Validar fortaleza de la contraseña (soporta UTF-8)
        if (mb_strlen($nueva, 'UTF-8') < 8 || !preg_match('/[A-Z]/u', $nueva) || !preg_match('/[a-z]/u', $nueva) || !preg_match('/[0-9]/u', $nueva)) {
            $error = 'La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas y números.';
        } else {
            $nueva_hash = password_hash($nueva, PASSWORD_DEFAULT);
            $email = strtolower(trim($email));
            // Buscar primero en users
            $stmt = $conn->prepare("SELECT id FROM users WHERE LOWER(email)=?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $stmt->close();
                $stmt = $conn->prepare("UPDATE users SET password=? WHERE LOWER(email)=?");
                $stmt->bind_param('ss', $nueva_hash, $email);
                if ($stmt->execute()) {
                    unset($_SESSION['reset_verified']);
                    unset($_SESSION['reset_email']);
                    unset($_SESSION['reset_table']);
                    $success = '¡Contraseña actualizada correctamente! Ahora puedes iniciar sesión.';
                } else {
                    $error = 'Error al actualizar la contraseña en users: ' . $stmt->error . ' | Email: ' . htmlspecialchars($email);
                }
            } else {
                $stmt->close();
                // CORREGIDO: usar id_usuario en vez de id
                $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE LOWER(email)=?");
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                    $stmt->close();
                    $stmt = $conn->prepare("UPDATE usuario SET password=? WHERE LOWER(email)=?");
                    $stmt->bind_param('ss', $nueva_hash, $email);
                    if ($stmt->execute()) {
                        unset($_SESSION['reset_verified']);
                        unset($_SESSION['reset_email']);
                        unset($_SESSION['reset_table']);
                        $success = '¡Contraseña actualizada correctamente! Ahora puedes iniciar sesión.';
                    } else {
                        $error = 'Error al actualizar la contraseña en usuario: ' . $stmt->error . ' | Email: ' . htmlspecialchars($email);
                    }
                } else {
                    $error = 'El usuario no existe. Email: ' . htmlspecialchars($email);
                }
            }
        }
    }
    // Formulario visual igual
    echo '<!DOCTYPE html>';
    echo '<html lang="es">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>Nueva Contraseña</title>';
    echo '<link rel="stylesheet" href="style.css">';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '</head>';
    echo '<body>';
    echo '<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: url(\'img/img11.png\') no-repeat center center fixed; background-size: cover;">';
    echo '<div class="card shadow border-0 rounded-4" style="max-width:400px; width:100%;">';
    echo '<div class="card-body p-4">';
    echo '<h2 class="mb-4 text-center" style="font-family:Montserrat, sans-serif; font-weight:700; color:#222; font-size:1.4rem;">Nueva Contraseña</h2>';
    if (!empty($error)) {
        echo '<div class="alert alert-danger text-center">'.$error.'</div>';
    }
    if (!empty($success)) {
        echo '<div class="alert alert-success text-center">'.$success.'</div>';
        echo '<div class="text-center mt-3">';
        echo '<a href="index.php" class="btn btn-primary w-100 mb-2">Volver al inicio de sesión</a>';
        echo '</div>';
    } else {
        echo '<form method="post">';
        echo '<input type="password" name="nueva_password" class="form-control mb-3" placeholder="Nueva contraseña" required autofocus>';
        echo '<button type="submit" class="btn btn-primary w-100 mb-2">Guardar</button>';
        echo '<a href="index.php" class="btn btn-link w-100">Volver al inicio de sesión</a>';
        echo '</form>';
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</body>';
    echo '</html>';
    exit;
}

// Si no, redirige al login y muestra el modal de login por defecto
header('Location: index.php?active_form=login');
exit;
?>
