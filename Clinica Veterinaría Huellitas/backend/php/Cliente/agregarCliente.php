<?php

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinica-veterinaria"; 

// Función para obtener los datos de un cliente 
function obtenerDatosCliente($servername, $username, $password, $dbname, $clienteId) {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Definir la consulta SQL para obtener los datos del cliente
    $sql = "SELECT nombre, apellidos, telefono, correo, direccion FROM clientes WHERE id = ?";

    $stmt = $conn->prepare($sql);

    // Vincular el parámetro de la consulta
    $stmt->bind_param("i", $clienteId);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row; // Devuelve los datos del cliente como un arreglo asociativo
    } else {
        return null; // El cliente no fue encontrado
    }
}

// Variables para almacenar los datos del formulario
$nombre = $apellidos = $telefono = $correo = $direccion = "";

// Verificar si se ha enviado un formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];

    // Validar los datos 
    if (empty($nombre) || empty($apellidos) || empty($telefono) || empty($correo) || empty($direccion)) {
        echo "Por favor, completa todos los campos obligatorios.";
    } else {
        // Llamar a la función para agregar el cliente a la base de datos
        $exito = agregarCliente($servername, $username, $password, $dbname, $nombre, $apellidos, $telefono, $correo, $direccion);

        if ($exito) {
            echo "Cliente agregado correctamente.";
        } else {
            echo "Error al agregar el cliente. Por favor, inténtalo de nuevo.";
        }
    }
}

// Función para agregar un cliente a la base de datos
function agregarCliente($servername, $username, $password, $dbname, $nombre, $apellidos, $telefono, $correo, $direccion) {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Definir la consulta SQL para insertar el cliente en la base de datos
    $sql = "INSERT INTO clientes (nombre, apellidos, telefono, correo, direccion) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    // Vincular los parámetros de la consulta
    $stmt->bind_param("sssss", $nombre, $apellidos, $telefono, $correo, $direccion);
    
    if ($stmt->execute()) {
        // Inserción exitosa
        $stmt->close();
        $conn->close();
        return true;
    } else {
        // Error en la inserción
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
    <title>Registrar Cliente</title>
</head>
<body>
    <h1>Registro del Nuevo Cliente</h1>
    <form method="POST" action="">
        <label for="nombreCliente">Nombre:</label>
        <input type="text" id="nombreCliente" name="nombreCliente" required>

        <label for="apellidosCliente">Apellidos:</label>
        <input type="text" id="apellidosCliente" name="apellidosCliente" required>

        <label for="telefonoCliente">Teléfono:</label>
        <input type="tel" id="telefonoCliente" name="telefonoCliente" required>

        <label for="correoCliente">Correo Electrónico:</label>
        <input type="email" id="correoCliente" name="correoCliente" required>

        <label for="direccionCliente">Dirección:</label>
        <input type="text" id="direccionCliente" name="direccionCliente" required>

        <input type="submit" value="Registrar Cliente">
    </form>
</body>
</html>