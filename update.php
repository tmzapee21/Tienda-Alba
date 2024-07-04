<?php
// Asumiendo que ya tienes una conexión a la base de datos establecida en $conn
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

// Limpiar y validar los datos obtenidos de $_POST
$nombreProducto = isset($_POST['Nombre_Producto']) ? filter_var($_POST['Nombre_Producto'], FILTER_SANITIZE_STRING) : '';
$descripcionProducto = isset($_POST['Descripcion_Producto']) ? filter_var($_POST['Descripcion_Producto'], FILTER_SANITIZE_STRING) : '';
$cantidad = isset($_POST['Cantidad']) ? filter_var($_POST['Cantidad'], FILTER_SANITIZE_NUMBER_INT) : 0;
$precio = isset($_POST['Precio']) ? filter_var($_POST['Precio'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : 0.0;
$correo = isset($_POST['Correo']) ? filter_var($_POST['Correo'], FILTER_SANITIZE_EMAIL) : '';
$direccion = isset($_POST['Direccion']) ? filter_var($_POST['Direccion'], FILTER_SANITIZE_STRING) : '';
$telefono = isset($_POST['Telefono']) ? filter_var($_POST['Telefono'], FILTER_SANITIZE_STRING) : '';
$pago = isset($_POST['Pago']) ? filter_var($_POST['Pago'], FILTER_SANITIZE_STRING) : '';
$facturaId = isset($_POST['ID_Boleta']) ? filter_var($_POST['ID_Boleta'], FILTER_SANITIZE_NUMBER_INT) : 0;

// Preparar la consulta SQL para actualizar los datos
$sql = "UPDATE factura SET Nombre_Producto=?, Descripcion_Producto=?, Cantidad=?, Precio=?, Correo=?, Direccion=?, Telefono=?, Pago=? WHERE ID_Boleta = ?";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ssidsissi", $nombreProducto, $descripcionProducto, $cantidad, $precio, $correo, $direccion, $telefono, $pago, $facturaId);
    
    // Ejecutar el statement
if ($stmt->execute()) {
    header("Location: factura.php"); // Redirige a factura.php
    exit;
} else {
    echo "Error al actualizar los datos.";
}

    // Cerrar el statement
    $stmt->close();
} else {
    echo "Error al preparar la consulta.";
}

// Cerrar la conexión
$conn->close();
?>



