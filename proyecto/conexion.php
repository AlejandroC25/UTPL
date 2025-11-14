<?php
// Configuración de la Base de Datos
$host = 'localhost';
$usuario_db = 'root'; // Usuario de MySQL en WAMP
$contrasena_db = ''; // Contraseña
$nombre_db = 'login_db'; // Nombre de la base de datos

// Conexión
$conexion = new mysqli($host, $usuario_db, $contrasena_db, $nombre_db);

// Verificación de error
if ($conexion->connect_error) {
    die('Error de Conexión a la Base de Datos: ' . $conexion->connect_error);
}

// Establecer conjunto de caracteres
$conexion->set_charset('utf8');
?>