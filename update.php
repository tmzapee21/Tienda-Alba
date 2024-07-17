<?php

include 'db.php'; // Conexión a la base de datos

// Recuperar datos del formulario utilizando filter_input()
$facturaId = filter_input(INPUT_POST, 'ID_Boleta', FILTER_SANITIZE_NUMBER_INT);
$nombreProducto = filter_input(INPUT_POST, 'Nombre_Producto', FILTER_SANITIZE_STRING);
$descripcionProducto = filter_input(INPUT_POST, 'Descripcion_Producto', FILTER_SANITIZE_STRING);
$cantidad = filter_input(INPUT_POST, 'Cantidad', FILTER_SANITIZE_NUMBER_INT);
$precio = filter_input(INPUT_POST, 'Precio', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$correo = filter_input(INPUT_POST, 'Correo', FILTER_SANITIZE_EMAIL);
$direccion = filter_input(INPUT_POST, 'Direccion', FILTER_SANITIZE_STRING);
$telefono = filter_input(INPUT_POST, 'Telefono', FILTER_SANITIZE_STRING);
$pago = filter_input(INPUT_POST, 'Pago', FILTER_SANITIZE_STRING);
$rut = filter_input(INPUT_POST, 'rut', FILTER_SANITIZE_STRING);
$estado = filter_input(INPUT_POST, 'Estado', FILTER_SANITIZE_STRING);

// Verifica la conexión a la base de datos
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para actualizar la factura
$sql = "UPDATE factura SET Nombre_Producto=?, Descripcion_Producto=?, Cantidad=?, Precio=?, Correo=?, Direccion=?, Telefono=?, Pago=?, Estado=?, Rut_cliente=? WHERE ID_Boleta=?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ssidsissisi", $nombreProducto, $descripcionProducto, $cantidad, $precio, $correo, $direccion, $telefono, $pago, $estado, $rut, $facturaId);

    // Ejecutar la consulta y verificar si se actualizó correctamente
    if ($stmt->execute()) {
        header("Location: factura.php");
        exit;
    } else {
        echo "Error al actualizar los datos: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error al preparar la consulta: " . $conn->error;
}

$conn->close();
?>
