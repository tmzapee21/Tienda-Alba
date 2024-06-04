<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM Usuarios WHERE correo = '$email' AND contrasena = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo json_encode(array("success" => true));
} else {
  echo json_encode(array("success" => false));
}

$conn->close();
?>