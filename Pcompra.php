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

        // Modificar esta consulta para incluir los datos de los productos
        // Nota: Esta es una simplificación. Deberías ajustarla según tu esquema de base de datos y necesidades.
        $sqlInicial = "INSERT INTO factura (Nombre_Empresa, Estado, EstadoB, Correo, Direccion, Telefono, Pago, Usuario, Rut_Cliente, Nombre_Producto, Descripcion_Producto, Cantidad, Precio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtInicial = $conn->prepare($sqlInicial);

        // Asumiendo que solo insertarás un producto por factura en este ejemplo simplificado
        $nombreProducto = $productos[0]['nombreProducto'] ?? 'Nombre no proporcionado';
        $descripcionProducto = $productos[0]['descripcionProducto'] ?? 'Descripción no proporcionada';
        $cantidad = $productos[0]['cantidad'] ?? 0;
        $precio = $productos[0]['precio'] ?? 0.0;

        $stmtInicial->bind_param("sssssssssssid", $nombreEmpresa, $estado, $estadoBoleta, $correo, $direccion, $telefono, $opciones, $usuario, $rutCliente, $nombreProducto, $descripcionProducto, $cantidad, $precio);
        $stmtInicial->execute();

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