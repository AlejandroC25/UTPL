<?php
// INICIAR LA SESIÓN
session_start();

// CONTROL DE ACCESO (Página Protegida)
// Si la variable de sesión 'usuario_id' NO existe, redirigimos al login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Si la ejecución llega aquí, el usuario SÍ está logueado
$nombre_usuario = $_SESSION['usuario_nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido - Área Restringida</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        /* Estilos  */
        body { background-color: #e6ffe6; }
        h1 { color: green; }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>¡Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?>!</h1>

        <p>Esta es la **PÁGINA PROTEGIDA**. Esto cumple con el requisito de control de acceso.</p>

        <p><a href="cerrar_sesion.php">Cerrar Sesión</a></p>
    </div>
</body>
</html>