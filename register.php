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

// Consulta para verificar si el correo electrónico o el RUT ya existen
$checkSql = "SELECT * FROM Usuarios WHERE correo = '$email' OR rut = '$rut'";
$checkResult = $conn->query($checkSql);

if ($checkResult->num_rows > 0) {
    // Si la consulta devuelve un resultado, el correo electrónico o el RUT ya existen
    echo json_encode(array("exists" => true));
} else {
    // Si la consulta no devuelve un resultado, el correo electrónico y el RUT no existen y puedes insertar el nuevo registro
    $sql = "INSERT INTO Usuarios (correo, username, rut, contrasena)
    VALUES ('$email', '$username', '$rut', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("success" => true)); // Devuelve una respuesta JSON en lugar de redirigir
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>