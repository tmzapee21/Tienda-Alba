<?php
session_start();

// Asegurarse de que el carrito existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Obtener el producto del cuerpo de la solicitud
$datosCrudos = file_get_contents('php://input');
$_POST = json_decode($datosCrudos, true);

// Verificar si los datos son válidos
if ($_POST && isset($_POST['nombre'], $_POST['cantidad'], $_POST['precio'])) {
    $nombreProducto = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];

    // Agregar el producto al carrito
    $_SESSION['carrito'][] = ['nombre' => $nombreProducto, 'cantidad' => $cantidad, 'precio' => $precio];
} else {
    // Manejar el error de datos faltantes o inválidos
    echo json_encode(['error' => 'Datos del producto faltantes o inválidos']);
    exit;
}

// Generar el HTML para el carrito
$htmlCarrito = '';
foreach ($_SESSION['carrito'] as $producto) {
    $htmlCarrito .= "<p>{$producto['nombre']} - Cantidad: {$producto['cantidad']} - Precio: {$producto['precio']}</p>";
}

// Devolver el HTML actualizado
echo json_encode(['htmlCarrito' => $htmlCarrito]);