<?php
// ----------------------------------------------------
// 1. Inicialización y Configuración
// ----------------------------------------------------
$nombre = $correo = $mensaje = '';
$errores = [];
$archivo_datos = 'mensajes.txt'; // <-- Archivo de destino

// ----------------------------------------------------
// 2. Procesamiento del Formulario (Si se envió por POST)
// ----------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Función de saneamiento
    function sanear_dato($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data); 
        return $data;
    }

    // A. Recolección y Saneamiento de Datos
    $nombre = sanear_dato($_POST['nombre'] ?? '');
    $correo = sanear_dato($_POST['correo'] ?? '');
    $mensaje = sanear_dato($_POST['mensaje'] ?? '');

    // B. Validación en el Servidor (PHP)
    if (empty($nombre)) { $errores[] = "El nombre es obligatorio."; } 
    if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) { $errores[] = "El formato del correo es inválido."; }
    if (empty($mensaje) || strlen($mensaje) < 10) { $errores[] = "El mensaje debe tener al menos 10 caracteres."; }

    // C. Manejo de Resultados (Guardado en Archivo de Texto)
    if (empty($errores)) {
        
        // 1. Formato de los datos: Fecha | Nombre | Correo | Mensaje
        $linea_datos = date('Y-m-d H:i:s') . " | Nombre: " . $nombre . " | Correo: " . $correo . " | Mensaje: " . str_replace(["\r", "\n"], " ", $mensaje) . "\n";
        
        // 2. Guardar en el archivo: FILE_APPEND añade al final del archivo
        if (file_put_contents($archivo_datos, $linea_datos, FILE_APPEND | LOCK_EX) !== false) {
            
            // Éxito: Redirigir a la página de confirmación
            header('Location: gracias.html');
            exit;
            
        } else {
            // Error al guardar el archivo (ej. permisos del servidor)
            $errores[] = "Hubo un problema de permisos al guardar los datos en el servidor.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Mi Sitio Web Personal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Mi Sitio Web Personal</h1>
        <nav>
            <ul>
                <li><a href="index.html">Inicio</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Contáctame</h2>
            <p>Usa el siguiente formulario para enviarme un mensaje directo.</p>
            
            <?php 
            // 3. Mostrar Mensajes de Error (Si hay errores de validación o de DB)
            if (!empty($errores)) {
                echo '<div class="mensaje-error">';
                foreach ($errores as $error) {
                    echo "<p>❌ " . $error . "</p>";
                }
                echo '</div>';
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                
                <div class="form-group">
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" id="nombre" name="nombre" required 
                           pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+" 
                           title="Solo letras y espacios."
                           value="<?php echo $nombre; ?>">
                </div>

                <div class="form-group">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" required 
                           value="<?php echo $correo; ?>">
                </div>

                <div class="form-group">
                    <label for="mensaje">Mensaje:</label>
                    <textarea id="mensaje" name="mensaje" required 
                              minlength="10"><?php echo $mensaje; ?></textarea>
                </div>

                <button type="submit" class="btn-submit">Enviar Mensaje</button>
            </form>

        </section>
    </main>

    <footer>
        <p>&copy; 2025 [Tu Nombre]. Todos los derechos reservados.</p>
    </footer>
</body>
</html>