<?php
// Incluye el archivo PHP y obtén los productos
$productos = include 'productos.php';

// Obtén la consulta de búsqueda del formulario
$busqueda = $_GET['q'];

// Filtra los productos basándose en la consulta de búsqueda
$productosFiltrados = array_filter($productos, function($producto) use ($busqueda) {
    return strpos(strtolower($producto["Nombre_producto"]), strtolower($busqueda)) !== false;
});

// Muestra los productos filtrados
foreach ($productosFiltrados as $producto) {
    // Aquí puedes mostrar cada producto como prefieras
    echo '<div class="col-md-3">
            <div class="card" style="width: 18rem;">
                <img src="' . $producto["Imagen"] . '" class="card-img-top" alt="' . $producto["Nombre_producto"] . '">
                <div class="card-body">
                    <h5 class="card-title">' . $producto["Nombre_producto"] . '</h5>
                    <p class="card-text">' . $producto["Descripcion"] . '</p>
                    <p class="card-text">Precio: $' . $producto["Precio"] . '</p>
                    <p class="card-text">Stock: ' . $producto["Stock"] . '</p>
                    <input type="number" id="cantidad' . $producto["id"] . '" value="1" min="1" max="' . $producto["Stock"] . '">
                    <button class="btn btn-primary" onclick="agregarAlCarrito(\'' . $producto["id"] . '\', \'' . $producto["Nombre_producto"] . '\', \'' . $producto["Descripcion"] . '\', document.getElementById(\'cantidad' . $producto["id"] . '\').value, \'' . $producto["Precio"] . '\', \'' . $producto["Imagen"] . '\')">Agregar al carrito</button>
                </div>
            </div>
        </div>';
}
?>