<?php

if (!empty($_POST["btnregistrar"])) {
    if (!empty($_POST["txtusuario"]) && !empty($_POST["txtemail"]) && !empty($_POST["txtpassword"])) {
        $usuario = $_POST["txtusuario"];
        $email = $_POST["txtemail"];
        $password = md5($_POST["txtpassword"]);
        $rol = 'user';

        $sql = $conexion->query("SELECT count(*) as 'total' FROM users WHERE usuario='$usuario'");

        if ($sql && $sql->fetch_object()->total > 0) {
            ?>
            <script>
                $(function notificacion() {
                    new PNotify({
                        title: "ERROR",
                        type: "error",
                        text: "El usuario <?= htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') ?> ya existe",
                        styling: "bootstrap3"
                    });
                });
            </script>
            <?php
        } else {
            $registro = $conexion->query("INSERT INTO users(usuario, email, password) VALUES ('$usuario', '$email', '$password')");

            if ($registro === true) {
                ?>
                <script>
                    $(function notificacion() {
                        new PNotify({
                            title: "CORRECTO",
                            type: "success",
                            text: "El usuario se ha registrado correctamente",
                            styling: "bootstrap3"
                        });
                    });
                </script>
                <?php
            } else {
                ?>
                <script>
                    $(function notificacion() {
                        new PNotify({
                            title: "INCORRECTO",
                            type: "error",
                            text: "Error al registrar usuario. Detalles: <?= htmlspecialchars($conexion->error, ENT_QUOTES, 'UTF-8') ?>",
                            styling: "bootstrap3"
                        });
                    });
                </script>
                <?php
            }
        }
    } else {
        ?>
        <script>
            $(function notificacion() {
                new PNotify({
                    title: "ERROR",
                    type: "error",
                    text: "Los campos estan vacios",
                    styling: "bootstrap3"
                });
            });
        </script>
        <?php
    }
}

?>