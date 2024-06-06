<?php
// tienda.php
session_start();
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="CSS/tienda.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Tienda Oficial de Vidal</title>
</head>

<body>

  <div id="contenedor">
    <div class="loader">
      <div class="leaf"></div>
      <div class="leaf"></div>
      <div class="leaf"></div>
    </div>
  </div>

  <div class="base" id="content" style="display: none;">






    <nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.html">
          <img src="IMG/Colo_a.png" alt="Logo" height="40">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            <li class="nav-item">
              <a class="nav-link" href="tienda.php">CATALOGO</a>
            </li>

            <a class="nav-link" onclick="mostrarCarrito()" data-bs-toggle="modal" data-bs-target="#carritoModal">CARRITO</a>

          </ul>
          <form class="d-flex" role="search">
            <input class="form-control me-3" type="search" placeholder="Buscar" aria-label="Search">
            <button class="btn btn-outline-light" type="submit">Buscar</button>
          </form>
          <div id="UsuarioNew">

            <?php
    echo "<span class='UsuarioNew'>Bienvenido, " . $_SESSION['username'] . "</span>";
    ?>
            <button onclick="location.href='cerrar.php'" class="btn btn-outline-light">CERRAR SESIÓN</button>
          </div>
          <div class="navbar-text">
          </div>
        </div>
      </div>
    </nav>


    <div id="videoCarousel" class="carousel slide custom-carousel" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <video class="d-block w-100" autoplay loop muted>
            <source src="VIDEO/Colo.mp4" type="video/mp4">
            Tu navegador no soporta la reproducción de video.
          </video>
        </div>
      </div>
    </div>

    <!-- Cartas Productos-->

    <!-- Cartas Productos-->

<?php
// Incluye el archivo PHP y obtén los productos
$productos = include 'productos.php';
?>

<!-- Aquí va el resto de tu código HTML -->

<!-- Luego, en el lugar donde quieres mostrar las tarjetas, puedes hacer algo como esto: -->
<div class="container">
  <div class="row">
  <?php foreach ($productos as $producto): ?>
    <div class="col-md-3">
      <div class="card" style="width: 18rem;">
        <img src="<?php echo $producto["Imagen"]; ?>" class="card-img-top" alt="<?php echo $producto["Nombre_producto"]; ?>">
        <div class="card-body">
          <h5 class="card-title"><?php echo $producto["Nombre_producto"]; ?></h5>
          <p class="card-text"><?php echo $producto["Descripcion"]; ?></p>
          <p class="card-text">Precio: $<?php echo $producto["Precio"]; ?></p>
          <p class="card-text">Stock: <?php echo $producto["Stock"]; ?></p>
          <input type="number" id="cantidad<?php echo $producto["id"]; ?>" value="1" min="1" max="<?php echo $producto["Stock"]; ?>">
          <button class="btn btn-primary" onclick="agregarAlCarrito('<?php echo $producto["id"]; ?>', '<?php echo $producto["Nombre_producto"]; ?>', '<?php echo $producto["Descripcion"]; ?>', document.getElementById('cantidad<?php echo $producto["id"]; ?>').value, '<?php echo $producto["Precio"]; ?>')">Agregar al carrito</button>
        </div>
      </div>
    </div>
<?php endforeach; ?>
  </div>
</div>

<div class="modal fade" id="carritoModal" tabindex="-1" aria-labelledby="carritoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="carritoModalLabel">Carrito de compras</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="productosCarrito">
            <!-- Aquí se mostrarán los productos del carrito -->
          </div>
          <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
    <a href="factura.html" class="btn btn-primary" onclick="comprar()">Comprar</a>
</div>
        </div>
      </div>
    </div>








    <audio id="miAudio" controls autoplay style="display: none;">
      <source src="MUSIC/Grande_Colocolo.mp3" type="audio/mpeg">
      Tu navegador no soporta la reproducción de audio.
    </audio>

  </div>

  <script>
var carrito = [];
var modal = new bootstrap.Modal(document.getElementById('carritoModal'));

function agregarAlCarrito(id, nombre, descripcion, cantidad, precio) {
    var productoExistente = carrito.find(producto => producto.id === id);

    if (productoExistente) {
        productoExistente.cantidad += parseInt(cantidad);
    } else {
        var producto = {
            id: id,
            nombre: nombre,
            descripcion: descripcion,
            cantidad: parseInt(cantidad), // Asegúrate de que la cantidad es un número
            precio: precio
        };

        carrito.push(producto);
    }

    var productosCarrito = document.getElementById('productosCarrito');
    productosCarrito.innerHTML = '';

    for (var i = 0; i < carrito.length; i++) {
        var precioTotal = (carrito[i].cantidad * carrito[i].precio).toFixed(3);
        productosCarrito.innerHTML += '<p>' + carrito[i].nombre + ' - ' + carrito[i].descripcion + ' - Cantidad: ' + carrito[i].cantidad + ' - Precio: ' + precioTotal + '</p>';
    }

    // Limpiar el input de cantidad
    document.getElementById('cantidad' + id).value = "1";

    // Alerta de producto agregado
    alert('Producto agregado al carrito');

    // Muestra el modal
    modal.show();
}
</script>
<script>
function mostrarCarrito() {
    var productosCarrito = document.getElementById('productosCarrito');
    productosCarrito.innerHTML = '';

    for (var i = 0; i < carrito.length; i++) {
        var precioTotal = (carrito[i].cantidad * carrito[i].precio).toFixed(3);
        productosCarrito.innerHTML += '<p>' + carrito[i].nombre + ' - ' + carrito[i].descripcion + ' - Cantidad: ' + carrito[i].cantidad + ' - Precio: ' + precioTotal + '</p>';
    }

    modal.show();
}
</script>


  <script src="carrito.js"></script>
  <script src="JS/musica.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script src="JS/loader.js"></script>
</body>

</html>