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

// Recoger los datos del carrito
$carrito = json_decode($_POST['carrito'], true);

// Insertar cada producto del carrito en la base de datos y actualizar el stock
foreach ($carrito as $producto) {
    // Insertar producto en el carrito
    $sql = "INSERT INTO carritos2 (producto_id, nombre, descripcion, cantidad, precio, imagen) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issids", $producto['id'], $producto['nombre'], $producto['descripcion'], $producto['cantidad'], $producto['precio'], $producto['imagen']);
    $stmt->execute();

    // Actualizar el stock del producto
    $sql = "UPDATE productos SET Stock = Stock - ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $producto['cantidad'], $producto['id']);
    $stmt->execute();
}

$stmt->close();
$conn->close();
?>