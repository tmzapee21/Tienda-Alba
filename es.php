
<?php


session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta SQL para actualizar el estado de la última boleta creada
$sqlUpdate = "UPDATE factura SET Estado = 'Creada' WHERE ID_Boleta IN (SELECT ID_Boleta FROM (SELECT ID_Boleta FROM factura WHERE Estado = 'Recien Creada' ORDER BY ID_Boleta DESC LIMIT 1) AS tmp)";

// Ejecutar la consulta SQL de actualización
if ($conn->query($sqlUpdate) === TRUE) {
    echo "Registro actualizado correctamente.";
} else {
    echo "Error al actualizar el registro: " . $conn->error;
}

$conn->close();
?>
