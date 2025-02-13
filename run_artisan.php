<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "lavcauqu_admin_lav";
$password = "@vZPH)X6=HrB";
$dbname = "lavcauqu_lav_db";

// Crear una conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "Conexión exitosa a la base de datos.<br>";

// Realizar una consulta SELECT
$sql = "SELECT * FROM usuarios"; // Ajusta la tabla según sea necesario
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Salida de los datos de cada fila
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Nombre: " . $row["nombre"]. "<br>";
    }
} else {
    echo "0 resultados";
}

// Cerrar la conexión
$conn->close();
?>
