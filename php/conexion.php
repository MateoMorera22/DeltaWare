<?php
$host = '127.0.0.1';
$user = 'root';
$password = ''; // Deja vacío si no tienes contraseña en MySQL
$database = 'sistema_gestion';

$conexion = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión a la base de datos: " . $conexion->connect_error]));
}
?>