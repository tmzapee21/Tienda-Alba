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

$sql = "SELECT * FROM Productos";
$result = $conn->query($sql);

$productos = array();

if ($result->num_rows > 0) {
  // Si la consulta devuelve un resultado, guarda los productos en el array $productos
  while($row = $result->fetch_assoc()) {
    $productos[] = $row;
  }
}

$conn->close();

// Devuelve el array $productos
return $productos;
?>

