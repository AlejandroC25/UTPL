<?php
// INICIO DE SESIÓN
session_start();

// Redirigir si ya está autenticado
if (isset($_SESSION['usuario_id'])) {
    header('Location: bienvenida.php');
    exit;
}

require_once 'conexion.php';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $conexion->real_escape_string($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    // Consulta para buscar el usuario por correo
    $stmt = $conexion->prepare("SELECT id, nombre, contrasena FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();

        // Seguridad: Verificar la contraseña con el HASH
        if (password_verify($contrasena, $usuario['contrasena'])) {
            // Establecer variables de SESIÓN
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];

            // Redirigir al área protegida
            header('Location: bienvenida.php');
            exit;
        } else {
            $mensaje = '<p class="error">Contraseña incorrecta.</p>';
        }
    } else {
        $mensaje = '<p class="error">Usuario no encontrado.</p>';
    }

    $stmt->close();
}
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedor">
        <h2>Iniciar Sesión</h2>
        <?php echo $mensaje; ?>
        <form action="login.php" method="POST">
            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <input type="submit" value="Acceder">
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
    </div>
</body>
</html>