<?php
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

$sql = "INSERT INTO Productos (Nombre_producto, Descripcion, Imagen, Precio, Stock) 
        VALUES ('Replica Copa', 'Replica Copa Libertadores', 'IMG/replica_copa.jpg', 299.990, 60)";

if ($conn->query($sql) === TRUE) {
  echo "Nuevo producto insertado con éxito.";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>