<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $opciones = $_POST['opciones'] ?? '';
    $rutCliente = $_POST['rut'] ?? '';
    $usuario = $_SESSION['username'] ?? '';
    $productosCarrito = $_POST['productosCarrito'] ?? ''; // Se espera que esta sea la cadena JSON de los productos

    // Decodificar JSON
    $productos = json_decode($productosCarrito, true);

    // Verificar que la decodificación fue exitosa y que productos no está vacío
    if (json_last_error() !== JSON_ERROR_NONE || empty($productos)) {
        echo "Error en la decodificación del JSON o el carrito está vacío.";
        exit; // Detener la ejecución si hay un error
    }

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        $nombreEmpresa = "Tienda Alba";
        $estadoBoleta = "Por Entregar";
        $estado = "Recien Creada";

        // Insertar la factura sin los detalles del producto
        $sqlFactura = "INSERT INTO factura (Nombre_Empresa, Estado, EstadoB, Correo, Direccion, Telefono, Pago, Usuario, Rut_Cliente) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtFactura = $conn->prepare($sqlFactura);
        $stmtFactura->bind_param("sssssssss", $nombreEmpresa, $estado, $estadoBoleta, $correo, $direccion, $telefono, $opciones, $usuario, $rutCliente);
        $stmtFactura->execute();
        $idBoleta = $conn->insert_id; // Recuperar el ID de la factura insertada

        // Insertar cada producto en la factura
        $sqlProducto = "INSERT INTO detalle_factura (ID_Boleta, Nombre_Producto, Descripcion_Producto, Cantidad, Precio) VALUES (?, ?, ?, ?, ?)";
        $stmtProducto = $conn->prepare($sqlProducto);

        foreach ($productos as $producto) {
            $nombreProducto = $producto['nombreProducto'] ?? 'Nombre no proporcionado';
            $descripcionProducto = $producto['descripcionProducto'] ?? 'Descripción no proporcionada';
            $cantidad = $producto['cantidad'] ?? 0;
            $precio = $producto['precio'] ?? 0.0;

            $stmtProducto->bind_param("issid", $idBoleta, $nombreProducto, $descripcionProducto, $cantidad, $precio);
            $stmtProducto->execute();
        }

        // Si todo fue bien, confirmar la transacción
        $conn->commit();
        echo 'success';
    } catch (Exception $e) {
        // Algo salió mal, revertir la transacción
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>