<?php

$nombreCliente = $fechaCita = $horaCita = $telefonoCliente = "";
$errores = array();
$exito = false;

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinica-veterinaria";

// Comprobar si se ha enviado un formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $nombreCliente = $_POST['nombre'];
    $fechaCita = $_POST['fecha_cita'];
    $horaCita = $_POST['hora_cita'];
    $telefonoCliente = $_POST['telefono_cliente'];

    // Validar los datos
    if (empty($nombreCliente) || empty($fechaCita) || empty($horaCita)) {
        $errores[] = "Por favor, completa todos los campos obligatorios.";
    }
    // Si no hay errores de validación, intentar guardar la cita en la base de datos
    if (empty($errores)) {
        // Llamar a la función para guardar la cita en la base de datos
        $exito = guardarCita($servername, $username, $password, $dbname, $nombreCliente, $fechaCita, $horaCita, $telefonoCliente,$tipoCita);

        if ($exito) {
            echo "La cita se ha agregado correctamente.";
        } else {
            echo "Error al agregar la cita. Por favor, inténtalo de nuevo.";
        }
    } else {
        // Mostrar los mensajes de error
        foreach ($errores as $error) {
            echo $error . "<br>";
        }
    }
}

// Función para guardar la cita en la base de datos 
function guardarCita($servername, $username, $password, $dbname, $nombreCliente, $fechaCita, $horaCita, $telefonoCliente, $tipoCita)
{
    // Crear una conexión a la base de datos 
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica si hay errores de conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    // Definir la consulta SQL para insertar la cita en la base de datos
    $sql = "INSERT INTO citas (nombre_cliente, fecha_cita, hora_cita, telefono_cliente, tipo_cita) VALUES (?, ?, ?, ?, ?)";

    // Preparar la consulta SQL
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros de la consulta
    $stmt->bind_param("sssss", $nombreCliente, $fechaCita, $horaCita, $telefonoCliente, $tipoCita);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // La inserción fue exitosa
        $stmt->close();
        $conn->close();
        return true;
    } else {
        // Hubo un error en la inserción
        $stmt->close();
        $conn->close();
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cita</title>
</head>
<body>
    <h1>Agregar Cita</h1>
    <form method="POST" action="">
        <label for="nombre">Nombre y Apellidos:</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombreCliente); ?>" required>
        <br>
        <label for="fecha_cita">Fecha de la Cita:</label>
        <input type="date" name="fecha_cita" value="<?php echo htmlspecialchars($fechaCita); ?>" required>
        <br>
        <label for="hora_cita">Hora de la Cita:</label>
        <input type="time" name="hora_cita" value="<?php echo htmlspecialchars($horaCita); ?>" required>
        <br>
        <label for="telefono_cliente">Teléfono del Cliente:</label>
        <input type="tel" name="telefono_cliente" value="<?php echo htmlspecialchars($telefonoCliente); ?>">
        <br>
        <label for="tipo_cita">Tipo de Cita:</label>
        <select name="tipo_cita" required>
            <option value="Consulta">Consulta</option>
            <option value="Vacuna">Vacuna</option>
            <option value="Revision">Revisión</option>
            <option value="Peluqueria">Peluquería</option>
        </select>
        <br>
        <input type="submit" value="Agregar Cita">
    </form>
</body>
</html>