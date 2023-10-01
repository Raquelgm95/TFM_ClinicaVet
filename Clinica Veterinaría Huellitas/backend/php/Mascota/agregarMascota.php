<?php

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinica-veterinaria"; 

// Variables para almacenar los datos del formulario
$nombreMascota = $tipoMascota = $razaMascota = $edadMascota = $fechaNacimiento = $numeroChip = $sexoMascota = $pesoMascota = $observaciones = "";

// Verificar si se ha enviado un formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $nombreMascota = $_POST['nombreMascota'];
    $tipoMascota = $_POST['tipoMascota'];
    $razaMascota = $_POST['razaMascota'];
    $edadMascota = $_POST['edadMascota'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $numeroChip = $_POST['numeroChip'];
    $sexoMascota = $_POST['sexoMascota'];
    $pesoMascota = $_POST['pesoMascota'];
    $observaciones = $_POST['observaciones'];

    // Validar los datos
    if (empty($nombreMascota) || empty($tipoMascota) || empty($razaMascota) || empty($edadMascota) || empty($sexoMascota)) {
        echo "Por favor, completa todos los campos obligatorios.";
    } else {
        // Llamar a la función para agregar la mascota a la base de datos
        $exito = agregarMascota($servername, $username, $password, $dbname, $nombreMascota, $tipoMascota, $razaMascota, $edadMascota, $fechaNacimiento, $numeroChip, $sexoMascota, $pesoMascota, $observaciones);

        if ($exito) {
            echo "Mascota agregada correctamente.";
        } else {
            echo "Error al agregar la mascota. Por favor, inténtalo de nuevo.";
        }
    }
}

// Función para agregar una mascota a la base de datos
function agregarMascota($servername, $username, $password, $dbname, $nombreMascota, $tipoMascota, $razaMascota, $edadMascota, $fechaNacimiento, $numeroChip, $sexoMascota, $pesoMascota, $observaciones) {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Definir la consulta SQL para insertar la mascota en la base de datos
    $sql = "INSERT INTO mascotas (nombre, tipo, raza, edad, fecha_nacimiento, numero_chip, sexo, peso, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    // Vincular los parámetros de la consulta
    $stmt->bind_param("sssiisss", $nombreMascota, $tipoMascota, $razaMascota, $edadMascota, $fechaNacimiento, $numeroChip, $sexoMascota, $pesoMascota, $observaciones);
    
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
    <title>Registrar Mascota</title>
</head>
<body>
    <h1>Registro de la Mascota</h1>
    <form method="POST" action="/api/mascotas">
        <label for="nombreMascota">Nombre de la Mascota:</label>
        <input type="text" id="nombreMascota" name="nombreMascota" required>

        <label for="tipoMascota">Tipo de mascota:</label>
        <input type="text" id="tipoMascota" name="tipoMascota" required>

        <label for="razaMascota">Raza de la Mascota:</label>
        <input type="text" id="razaMascota" name="razaMascota" required>

        <label for="edadMascota">Edad:</label>
        <input type="number" id="edadMascota" name="edadMascota" required>

        <label for="fechaNacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fechaNacimiento" name="fechaNacimiento">

        <label for="numeroChip">Número de Chip:</label>
        <input type="text" id="numeroChip" name="numeroChip">

        <label>Sexo de la Mascota:</label>
        <div class="radio-group">
            <div>
                <input type="radio" id="macho" name="sexoMascota" value="macho" required>
                Macho
            </div>
            <div>
                <input type="radio" id="hembra" name="sexoMascota" value="hembra" required>
                Hembra
            </div>
        </div>

        <label for="pesoMascota">Peso de la Mascota (kg):</label>
        <input type="number" id="pesoMascota" name="pesoMascota">

        <label for="observaciones">Observaciones:</label>
        <textarea id="observaciones" name="observaciones" maxlength="500"></textarea>

        <input type="submit" value="Registrar Mascota">
    </form>
</body>
</html>