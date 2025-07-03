<?php 

session_start();
require_once 'modelo/conexion.php';

if (isset($_POST['register'])) {
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $checkEmail = $conexion->query("SELECT email FROM users WHERE email = '$email'");
    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = '¡El correo electrónico ya está registrado!';
        $_SESSION['active_form'] = 'register';
    } else {
        $conexion->query("INSERT INTO users (usuario, email, password, role) VALUES ('$usuario', '$email', '$password', '$role')");
    }

    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conexion->query("SELECT * FROM users WHERE email = '$email'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // LOG de depuración
        error_log('LOGIN: email=' . $email . ' hash=' . $user['password'] . ' pass_verify=' . (password_verify($password, $user['password']) ? 'OK' : 'FAIL'));
        if (password_verify($password, $user['password'])) {
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            // Guardar mensaje de bienvenida para mostrar en inicio.php
            $_SESSION['login_success'] = [
                'role' => $user['role'],
                'usuario' => $user['usuario']
            ];
            header("Location: vista/inicio.php");
            exit();
        }
    }
    
    $_SESSION['login_error'] = 'Correo o contraseña incorrectos';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}


?>