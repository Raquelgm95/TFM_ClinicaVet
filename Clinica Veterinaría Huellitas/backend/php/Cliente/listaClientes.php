<?php

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinica-veterinaria"; 


// Crear una nueva conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión tuvo éxito
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Incluir la función para editar un cliente
include("editarCliente.php");

// Consulta SQL para obtener la lista de clientes
$sql = "SELECT id, nombre, apellidos FROM clientes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h1>Lista de Clientes</h1>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        $clienteId = $row["id"];
        $nombreCliente = $row["nombre"] . " " . $row["apellidos"];
        echo "<li>$nombreCliente - <a href='editarCliente.php?clienteId=$clienteId'>Editar</a></li>";
    }
    echo "</ul>";
} else {
    echo "No hay clientes registrados.";
}

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

// Cerrar la conexión
$conn->close();
?>