<?php

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinica-veterinaria";

// Variables para almacenar los datos del cliente
$clienteId = $nombre = $apellidos = $telefono = $correo = $direccion = "";

// Verificar si se ha enviado un formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $clienteId = $_POST['clienteId'];
    $nombre = $_POST['nombreCliente'];
    $apellidos = $_POST['apellidosCliente'];
    $telefono = $_POST['telefonoCliente'];
    $correo = $_POST['correoCliente'];
    $direccion = $_POST['direccionCliente'];

    // Validar los datos
    if (empty($nombre) || empty($apellidos) || empty($telefono) || empty($correo) || empty($direccion)) {
        echo "Por favor, completa todos los campos obligatorios.";
    } else {
        // Llamar a la función para actualizar el cliente en la base de datos
        $exito = editarCliente($servername, $username, $password, $dbname, $clienteId, $nombre, $apellidos, $telefono, $correo, $direccion);

        if ($exito) {
            echo "Cliente editado correctamente.";
        } else {
            echo "Error al editar el cliente. Por favor, inténtalo de nuevo.";
        }
    }
}

// Función para editar un cliente en la base de datos
function editarCliente($servername, $username, $password, $dbname, $clienteId, $nombre, $apellidos, $telefono, $correo, $direccion) {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Definir la consulta SQL para actualizar el cliente en la base de datos
    $sql = "UPDATE clientes SET nombre=?, apellidos=?, telefono=?, correo=?, direccion=? WHERE id=?";

    $stmt = $conn->prepare($sql);

    // Vincular los parámetros de la consulta
    $stmt->bind_param("sssssi", $nombre, $apellidos, $telefono, $correo, $direccion, $clienteId);

    if ($stmt->execute()) {
        // Actualización exitosa
        $stmt->close();
        $conn->close();
        return true;
    } else {
        // Error en la actualización
        $stmt->close();
        $conn->close();
        return false;
    }
}

// Definir $datosCliente después de procesar los datos del formulario si se está realizando una edición
if (!empty($clienteId)) {
    // Verificar si se está editando un cliente existente
    $datosCliente = obtenerDatosCliente($servername, $username, $password, $dbname, $clienteId);

    if ($datosCliente) {
        // Los datos del cliente se han obtenido con éxito
        $nombre = $datosCliente['nombre'];
        $apellidos = $datosCliente['apellidos'];
        $telefono = $datosCliente['telefono'];
        $correo = $datosCliente['correo'];
        $direccion = $datosCliente['direccion'];

        // Cuando el formulario se muestre, los campos se rellenarán automáticamente con los datos actuales del cliente
        // Esto permite que el usuario edite los campos según sea necesario y luego los actualice
    } else {
        // Error al obtener los datos del cliente
        echo "Error al obtener los datos del cliente. Por favor, inténtalo de nuevo o verifica el cliente seleccionado.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
</head>
<body>
    <h1>Editar Cliente</h1>
    <form method="POST" action="editarCliente.php">
        <input type="hidden" name="clienteId" value="<?php echo $clienteId; ?>">
        <label for="nombreCliente">Nombre:</label>
        <input type="text" id="nombreCliente" name="nombreCliente" value="<?php echo $nombre; ?>" required>

        <label for="apellidosCliente">Apellidos:</label>
        <input type="text" id="apellidosCliente" name="apellidosCliente" value="<?php echo $apellidos; ?>" required>

        <label for="telefonoCliente">Teléfono:</label>
        <input type="tel" id="telefonoCliente" name="telefonoCliente" value="<?php echo $telefono; ?>" required>

        <label for="correoCliente">Correo Electrónico:</label>
        <input type="email" id="correoCliente" name="correoCliente" value="<?php echo $correo; ?>" required>

        <label for="direccionCliente">Dirección:</label>
        <input type="text" id="direccionCliente" name="direccionCliente" value="<?php echo $direccion; ?>" required>

        <input type="submit" value="Guardar Cambios">
    </form>
</body>
</html>