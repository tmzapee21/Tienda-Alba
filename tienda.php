<?php
session_start();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Recoger los datos del formulario
  $nombreProducto = $_POST['nombreProducto'] ?? '';
  $descripcionProducto = $_POST['descripcionProducto'] ?? '';
  $cantidad = $_POST['cantidad'] ?? '';
  $precio = $_POST['precio'] ?? '';
  $correo = $_POST['correo'] ?? '';
  $direccion = $_POST['direccion'] ?? '';
  $telefono = $_POST['telefono'] ?? '';
  $opciones = $_POST['opciones'] ?? '';
  $usuario = $_SESSION['username'];

  // Nombre de la empresa fijo
  $nombreEmpresa = "Tienda Alba";

  // Insertar los datos en la tabla
  $sql = "INSERT INTO factura (Nombre_Empresa, Nombre_Producto, Descripcion_Producto, Cantidad, Precio, Correo, Direccion, Telefono, Pago, usuario)
VALUES ('$nombreEmpresa', '$nombreProducto', '$descripcionProducto', '$cantidad', '$precio', '$correo', '$direccion', '$telefono', '$opciones', '$usuario')";

// Validar los campos aquí
if(empty($nombreProducto) || empty($descripcionProducto) || empty($cantidad) || empty($precio) || empty($correo) || empty($direccion) || empty($telefono) || empty($opciones)) {
  echo "Todos los campos son obligatorios.";
  return;
}

if(!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
  echo "Por favor, introduce un correo válido.";
  return;
}

if(!preg_match("/^[0-9]{9}$/", $telefono)) {
  echo "Por favor, introduce un número de teléfono válido.";
  return;
}

if($cantidad <= 0) {
  echo "La cantidad debe ser mayor que cero.";
  return;
}

if($precio <= 0) {
  echo "El precio debe ser mayor que cero.";
  return;
}

// Ejecutar la consulta SQL y redirigir
if ($conn->query($sql) === TRUE) {
  // Redirigir a factura.php
  echo 'success';
  return;
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="CSS/tienda.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
              <a class="nav-link" href="tienda.php">TIENDA</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="factura.php">BOLETAS</a>
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

    <!-- Inputs Productos-->

<!-- Inputs Productos-->
<div id="ventana55">
  <div class="form-container">
  <form method="post" action="tienda.php" onsubmit="return validarFormulario()">
        <div id="formContainer">
          <div id="leftSide">
            <div class="form-group">
              <label for="nombreProducto">Nombre de Producto</label>
              <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" placeholder="Introduce el nombre del producto">
              <span id="error-nombreProducto" class="error"></span>

            </div>
            <div class="form-group">
              <label for="descripcionProducto">Descripcion del producto</label>
              <input type="text" class="form-control" id="descripcionProducto" name="descripcionProducto" placeholder="Introduce la descripción del producto">
              <span id="error-descrip" class="error"></span>
            </div>
            <div class="form-group">
              <label for="cantidad">Cantidad</label>
              <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Introduce la cantidad">
              <span id="error-cantidad" class="error"></span>
            </div>
            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" placeholder="Introduce el precio">
                <span id="error-precio" class="error"></span>
            </div>
          </div>

          <div id="rightSide">
            <div class="form-group">
              <label for="correo">Correo</label>
              <input type="email" class="form-control" id="correo" name="correo" placeholder="Introduce el correo">
              <span id="error-correo" class="error"></span>
            </div>
            <div class="form-group">
              <label for="direccion">Dirección</label>
              <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Introduce la dirección">
              <span id="error-direc" class="error"></span>
            </div>
            <div class="form-group">
              <label for="telefono">Telefono</label>
              <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Introduce el teléfono">
              <span id="error-tele" class="error"></span>
            </div>
            <div class="form-group">
                <label for="opciones">Pago</label>
                <select class="form-control" id="opciones" name="opciones">
                    <option value="">Elija una opcion:</option>
                    <option value="Presencial">Presencial</option>
                    <option value="Transferencia">Transferencia</option>
                </select>
                <span id="error-pago" class="error"></span>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-outline-light" id="btn6">Comprar</button>
      </form>
    </div>
  </div>












    <audio id="miAudio" controls autoplay style="display: none;">
      <source src="MUSIC/Grande_Colocolo.mp3" type="audio/mpeg">
      Tu navegador no soporta la reproducción de audio.
    </audio>

  </div>
  <script>
    window.addEventListener('beforeunload', function (e) {
  if (!validarFormulario()) {
    e.preventDefault();
    e.returnValue = '';
  }
});
  </script>
  
  <script src="JS/validaciones.js"></script>
  <script src="JS/musica.js"></script>


  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="JS/loader.js"></script>
</body>

</html>