<?php

session_start();

function mostrarRutUsuario() {
  // Crear conexión
  $conn = new mysqli('localhost', 'root', '', 'usuarios');

  // Verificar conexión
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Recuperar el RUT del usuario actual
  $sql = "SELECT rut FROM usuarios WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $_SESSION['username']);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      // Devolver el RUT del usuario
      $row = $result->fetch_assoc();
      return $row['rut'];
  } else {
      return "No se encontró el RUT del usuario.";
  }

  $conn->close();
}

function mostrarCarrito() {
  // Crear conexión
  $conn = new mysqli('localhost', 'root', '', 'usuarios');

  // Verificar conexión
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Recuperar todos los productos en el carrito
  $sql = "SELECT * FROM carritos2";
  $result = $conn->query($sql);

  $subtotal = 0;
  $iva = 0.19; // Ajusta este valor al IVA correspondiente

  // Obtener el RUT del usuario
  $rutUsuario = mostrarRutUsuario();

  echo "<table id='tablaProductos'>";
  echo "<tr><th></th><th>Nombre del producto</th><th>RUT</th><th>Descripcion</th><th>Cantidad</th><th>Precio</th></tr>";

  if ($result->num_rows > 0) {
      // Mostrar los detalles de cada producto
      while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td><img src='" . $row['imagen'] . "' width='70' height='70'></td>";
          
          echo "<td>" . $row['nombre'] . "</td>";
          echo "<td>" . $rutUsuario . "</td>"; // Mostrar el RUT del usuario
          
echo "<td class='descripcion'>" . $row['descripcion'] . "</td>";
          echo "<td>" . $row['cantidad'] . "</td>";
          echo "<td>" . $row['precio'] . "</td>";
          
          echo "</tr>";

          $subtotal += $row['precio'] * $row['cantidad'];
      }
  } else {
      echo "No se encontraron productos en el carrito.";
  }

  echo "</table>";

  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="CSS/factura.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Factura</title>
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
        <a class="navbar-brand" href="tienda.php">
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

          </ul>
          
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

    <div class="card">
  <div class="card-body">
    <h1 id="boleta" class="card-title">Boleta</h1>
    <?php
    mostrarRutUsuario();
    mostrarCarrito();
    ?>
  </div>
</div>






  </div>


  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="JS/loader.js"></script>

</body>
</html>