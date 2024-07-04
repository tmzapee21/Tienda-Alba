<?php
// login.php
$host = "localhost";
$db   = "usuarios";
$user = "root";
$pass = "";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT username, rut FROM usuarios WHERE correo = ? AND contrasena = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    session_start();
    $_SESSION['correo'] = $email; // Aquí se define $_SESSION['correo']
    $_SESSION['username'] = $row['username'];
    $_SESSION['rut'] = $row['rut'];
    echo "success";
} else {
    echo "failure";
}

$stmt->close();
$conn->close();
?>