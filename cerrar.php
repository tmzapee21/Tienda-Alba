<?php
// En tu archivo cerrar.php

session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Elimina todos los registros de la tabla carritos2
$sql = "DELETE FROM carritos2";
if ($conn->query($sql) === TRUE) {
    echo "Records deleted successfully";
} else {
    echo "Error deleting records: " . $conn->error;
}

$conn->close();

// Elimina el carrito del usuario de la sesión
unset($_SESSION['carrito'][$_SESSION['username']]);

// Destruye la sesión
session_destroy();

// Redirige al usuario a la página de inicio de sesión
header('Location: index.html');
exit;
?>