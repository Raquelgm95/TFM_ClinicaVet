<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinica-veterinaria";  

// Conectar a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consultar todas las citas de la base de datos
$sql = "SELECT * FROM citas";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h1>Lista de Citas</h1>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Nombre del Cliente</th><th>Fecha de la Cita</th><th>Hora de la Cita</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_cita"] . "</td>";
        echo "<td>" . $row["nombre_cliente"] . "</td>";
        echo "<td>" . $row["fecha_cita"] . "</td>";
        echo "<td>" . $row["hora_cita"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No hay citas registradas.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>