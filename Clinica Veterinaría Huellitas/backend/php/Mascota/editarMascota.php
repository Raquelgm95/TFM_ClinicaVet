<?php

// Configuración de la conexión a la base de datos (reemplaza con tus propios valores)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinica-veterinaria";

// Variables para almacenar los datos de la mascota
$idMascota = $nombreMascota = $tipoMascota = $razaMascota = $edadMascota = $fechaNacimiento = $numeroChip = $sexoMascota = $pesoMascota = $observaciones = "";

// Verificar si se ha enviado un formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $idMascota = $_POST['idMascota'];
    $nombreMascota = $_POST['nombreMascota'];
    $tipoMascota = $_POST['tipoMascota'];
    $razaMascota = $_POST['razaMascota'];
    $edadMascota = $_POST['edadMascota'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $numeroChip = $_POST['numeroChip'];
    $sexoMascota = $_POST['sexoMascota'];
    $pesoMascota = $_POST['pesoMascota'];
    $observaciones = $_POST['observaciones'];

    // Llamar a la función para actualizar la mascota en la base de datos
    $exito = editarMascota($servername, $username, $password, $dbname, $idMascota, $nombreMascota, $tipoMascota, $razaMascota, $edadMascota, $fechaNacimiento, $numeroChip, $sexoMascota, $pesoMascota, $observaciones);

    if ($exito) {
        echo "Mascota actualizada correctamente.";
    } else {
        echo "Error al actualizar la mascota. Por favor, inténtalo de nuevo.";
    }
} else {
    // Recuperar el ID de la mascota de la URL
    if (isset($_GET['id'])) {
        $idMascota = $_GET['id'];

        // Llamar a la función para obtener los datos de la mascota
        $datosMascota = obtenerDatosMascota($servername, $username, $password, $dbname, $idMascota);

        if ($datosMascota) {
            // Asignar los datos de la mascota a las variables
            $nombreMascota = $datosMascota['nombre'];
            $tipoMascota = $datosMascota['tipo'];
            $razaMascota = $datosMascota['raza'];
            $edadMascota = $datosMascota['edad'];
            $fechaNacimiento = $datosMascota['fecha_nacimiento'];
            $numeroChip = $datosMascota['numero_chip'];
            $sexoMascota = $datosMascota['sexo'];
            $pesoMascota = $datosMascota['peso'];
            $observaciones = $datosMascota['observaciones'];
        } else {
            echo "No se encontró la mascota.";
        }
    }
}

// Función para obtener los datos de una mascota
function obtenerDatosMascota($servername, $username, $password, $dbname, $idMascota) {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta SQL para obtener los datos de la mascota por su ID
    $sql = "SELECT * FROM mascotas WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    
    // Vincular el parámetro de la consulta
    $stmt->bind_param("i", $idMascota);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $mascota = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $mascota;
    } else {
        $stmt->close();
        $conn->close();
        return false;
    }
}

// Función para editar una mascota en la base de datos
function editarMascota($servername, $username, $password, $dbname, $idMascota, $nombreMascota, $tipoMascota, $razaMascota, $edadMascota, $fechaNacimiento, $numeroChip, $sexoMascota, $pesoMascota, $observaciones) {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Definir la consulta SQL para actualizar la mascota en la base de datos
    $sql = "UPDATE mascotas SET nombre = ?, tipo = ?, raza = ?, edad = ?, fecha_nacimiento = ?, numero_chip = ?, sexo = ?, peso = ?, observaciones = ? WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    
    // Vincular los parámetros de la consulta
    $stmt->bind_param("sssiissssi", $nombreMascota, $tipoMascota, $razaMascota, $edadMascota, $fechaNacimiento, $numeroChip, $sexoMascota, $pesoMascota, $observaciones, $idMascota);
    
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

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mascota</title>
    <!-- Agrega tus estilos CSS aquí -->
</head>
<body>
    <h1>Editar Mascota</h1>
    <form method="POST" action="/api/mascotas">
        <input type="hidden" name="idMascota" value="<?php echo $idMascota; ?>">
        <label for="nombreMascota">Nombre de la Mascota:</label>
        <input type="text" id="nombreMascota" name="nombreMascota" value="<?php echo $nombreMascota; ?>" required>

        <label for="tipoMascota">Tipo de mascota:</label>
        <input type="text" id="tipoMascota" name="tipoMascota" value="<?php echo $tipoMascota; ?>" required>

        <label for="razaMascota">Raza de la Mascota:</label>
        <input type="text" id="razaMascota" name="razaMascota" value="<?php echo $razaMascota; ?>" required>

        <label for="edadMascota">Edad:</label>
        <input type="number" id="edadMascota" name="edadMascota" value="<?php echo $edadMascota; ?>" required>

        <label for="fechaNacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fechaNacimiento" name="fechaNacimiento" value="<?php echo $fechaNacimiento; ?>">

        <label for="numeroChip">Número de Chip:</label>
        <input type="text" id="numeroChip" name="numeroChip" value="<?php echo $numeroChip; ?>">

        <label>Sexo de la Mascota:</label>
        <div class="radio-group">
            <div>
                <input type="radio" id="macho" name="sexoMascota" value="macho" <?php if ($sexoMascota === 'macho') echo 'checked'; ?> required>
                Macho
            </div>
            <div>
                <input type="radio" id="hembra" name="sexoMascota" value="hembra" <?php if ($sexoMascota === 'hembra') echo 'checked'; ?> required>
                Hembra
            </div>
        </div>

        <label for="pesoMascota">Peso de la Mascota (kg):</label>
        <input type="number" id="pesoMascota" name="pesoMascota" value="<?php echo $pesoMascota; ?>">

        <label for="observaciones">Observaciones:</label>
        <textarea id="observaciones" name="observaciones" maxlength="500"><?php echo $observaciones; ?></textarea>

        <input type="submit" value="Guardar Cambios">
    </form>
</body>
</html>