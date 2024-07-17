<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ID_Boleta'])) {
        $facturaId = filter_var($_POST['ID_Boleta'], FILTER_SANITIZE_NUMBER_INT);
        $nombreProducto = filter_var($_POST['Nombre_Producto'], FILTER_SANITIZE_STRING);
        $descripcionProducto = filter_var($_POST['Descripcion_Producto'], FILTER_SANITIZE_STRING);
        $cantidad = filter_var($_POST['Cantidad'], FILTER_SANITIZE_NUMBER_INT);
        $precio = filter_var($_POST['Precio'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $rut = filter_var($_POST['rut'], FILTER_SANITIZE_STRING);
        $correo = filter_var($_POST['Correo'], FILTER_SANITIZE_EMAIL);
        $direccion = filter_var($_POST['Direccion'], FILTER_SANITIZE_STRING);
        $telefono = filter_var($_POST['Telefono'], FILTER_SANITIZE_STRING);
        $pago = filter_var($_POST['Pago'], FILTER_SANITIZE_STRING);
        $estado = filter_var($_POST['Estado'], FILTER_SANITIZE_STRING);

        if (filter_var($facturaId, FILTER_VALIDATE_INT)) {
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if (!empty($correo) && filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $stmt = $conn->prepare("UPDATE factura SET Nombre_Producto = ?, Descripcion_Producto = ?, Cantidad = ?, Precio = ?, Rut_cliente = ?, Correo = ?, Direccion = ?, Telefono = ?, Pago = ?, Estado = ? WHERE ID_Boleta = ?");
                $stmt->bind_param("ssidsissssi", $nombreProducto, $descripcionProducto, $cantidad, $precio, $rut, $correo, $direccion, $telefono, $pago, $estado, $facturaId);

                if ($stmt->execute()) {
                    header("Location: factura.php");
                    
                } else {
                    echo "Error al actualizar la factura: " . $stmt->error;
                }

                $stmt->close();
                $conn->close();
            } else {
                echo "Correo inválido o vacío.";
            }
        } else {
            echo "ID de factura inválido.";
        }
    } else {
        echo "ID de factura no proporcionado.";
    }
} else {
    echo "Método de solicitud no permitido";
}
?>