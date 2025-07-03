<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "sis_asistencia";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexion fallida: ". $conn->connect_error);
} 

?>