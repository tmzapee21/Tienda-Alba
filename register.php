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

$email = $_POST['email'];
$username = $_POST['username'];
$rut = $_POST['rut'];
$password = $_POST['password'];

$sql = "INSERT INTO Usuarios (correo, username, rut, contrasena)
VALUES ('$email', '$username', '$rut', '$password')";


if ($conn->query($sql) === TRUE) {
  header("Location: index.html"); // Redirige al usuario a index.html
  exit; // Asegúrate de llamar a exit después de header para detener la ejecución del script
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>