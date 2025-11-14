<?php
require_once 'conexion.php';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener y limpiar datos
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $correo = $conexion->real_escape_string($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    // Validación básica
    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        $mensaje = '<p class="error">Todos los campos son obligatorios.</p>';
    } else if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = '<p class="error">El formato del correo electrónico no es válido.</p>';
    } else {
        // Seguridad: Aplicar HASH a la contraseña
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Inserción segura
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $correo, $contrasena_hash);

        if ($stmt->execute()) {
            $mensaje = '<p class="exito">¡Registro exitoso! Ya puedes <a href="login.php">iniciar sesión</a>.</p>';
        } else {
            if ($conexion->errno == 1062) {
                $mensaje = '<p class="error">El correo electrónico ya está registrado.</p>';
            } else {
                $mensaje = '<p class="error">Error al registrar.</p>';
            }
        }
        $stmt->close();
    }
}
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedor">
        <h2>Registro de Usuario</h2>
        <?php echo $mensaje; ?>
        <form action="registro.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <input type="submit" value="Registrarse">
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a></p>
    </div>
</body>
</html>