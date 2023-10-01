<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinica-veterinaria"; 

// Comprobar si se ha enviado un formulario para editar la cita
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cita'])) {
    $idCita = $_POST['id_cita'];
    $nombreCliente = $_POST['nombre_cliente'];
    $fechaCita = $_POST['fecha_cita'];
    $horaCita = $_POST['hora_cita'];

    // Conectar a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Actualizar la cita en la base de datos
    $sql = "UPDATE citas SET nombre_cliente=?, fecha_cita=?, hora_cita=? WHERE id_cita=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombreCliente, $fechaCita, $horaCita, $idCita);

    if ($stmt->execute()) {
        // Actualización exitosa
        $stmt->close();
        $conn->close();
        header("Location: html/CitaModificada.html"); 
        exit();
    } else {
        echo "Error al actualizar la cita. Por favor, inténtalo de nuevo.";
    }
}

// Obtener el ID de la cita a editar desde la URL
if (isset($_GET['id'])) {
    $idCita = $_GET['id'];

    // Conectar a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consultar la cita por su ID
    $sql = "SELECT * FROM citas WHERE id_cita = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCita);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // La cita existe, obtener sus datos
        $row = $result->fetch_assoc();
        $nombreCliente = $row['nombre_cliente'];
        $fechaCita = $row['fecha_cita'];
        $horaCita = $row['hora_cita'];
    } else {
        echo "Cita no encontrada.";
        $stmt->close();
        $conn->close();
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID de cita no proporcionado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita</title>
</head>
<body>
    <h1>Editar Cita</h1>
    <form method="POST" action="">
        <input type="hidden" name="id_cita" value="<?php echo $idCita; ?>">
        <label for="nombre_cliente">Nombre del Cliente:</label>
        <input type="text" name="nombre_cliente" value="<?php echo htmlspecialchars($nombreCliente); ?>" required>
        <br>
        <label for="fecha_cita">Fecha de la Cita:</label>
        <input type="date" name="fecha_cita" value="<?php echo htmlspecialchars($fechaCita); ?>" required>
        <br>
        <label for="hora_cita">Hora de la Cita:</label>
        <input type="time" name="hora_cita" value="<?php echo htmlspecialchars($horaCita); ?>" required>
        <br>
        <input type="submit" value="Guardar Cambios">
    </form>
</body>
</html>