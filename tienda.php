<?php
session_start();
include 'db.php'; // Asegúrate de tener el archivo db.php con la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar los datos del formulario
    $nombreProducto = $_POST['nombreProducto'];
    $descripcionProducto = $_POST['descripcionProducto'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $rut = $_POST['rut'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $pago = $_POST['opciones'];

    // Insertar datos en la tabla factura
    $sql_insert_factura = "INSERT INTO factura (Nombre_Producto, Descripcion_Producto, Cantidad, Precio, Rut_cliente, Correo_cliente, Direccion_cliente, Telefono_cliente, Pago)
                           VALUES ('$nombreProducto', '$descripcionProducto', $cantidad, $precio, '$rut', '$correo', '$direccion', '$telefono', '$pago')";

    if ($conn->query($sql_insert_factura) === TRUE) {
        // Obtener el ID_Boleta generado
        $id_boleta = $conn->insert_id;

        // Insertar una fila en la tabla entrega con el ID_Boleta
        $sql_insert_entrega = "INSERT INTO entrega (ID_Boleta) VALUES ($id_boleta)";

        if ($conn->query($sql_insert_entrega) === TRUE) {
            echo "Boleta creada correctamente. ID Boleta: " . $id_boleta;
        } else {
            echo "Error al insertar en la tabla entrega: " . $conn->error;
        }
    } else {
        echo "Error al insertar en la tabla factura: " . $conn->error;
    }

    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="CSS/tienda.css">
  <link rel="stylesheet" href="CSS/tienda2.css">
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
              <a class="nav-link" href="tienda.php">NUEVA BOLETA</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="factura.php">BOLETAS</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="estado.php">CONFIRMACIONES</a>
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

    <!-- Inputs Productos-->

    <div id="contenedorPrincipal">
    
    <div id="contenedorCSS">


<!-- Inputs Productos-->

<div id="ventana55">
  <div class="form-container">
  <form method="post" action="tienda.php" onsubmit="return validarFormulario()">
        <div id="formContainer" style="display: flex;">

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
            <div class="form-group">
  <!-- Botón para agregar producto al carrito -->
  <button type="button" onclick="agregarProductoAlCarrito()">Agregar al Carrito</button>
</div>
          </div>

          <div id="rightSide">
          <div class="form-group">
              <label for="rut">Rut Cliente</label>
              <input type="rut" class="form-control" id="rut" name="rut" placeholder="Introduce el Rut">
              <span id="error-rut" class="error"></span>
            </div>
            <div class="form-group">
              <label for="correo">Correo Cliente</label>
              <input type="email" class="form-control" id="correo" name="correo" placeholder="Introduce el correo">
              <span id="error-correo" class="error"></span>
            </div>
            <div class="form-group">
              <label for="direccion">Dirección Cliente</label>
              <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Introduce la dirección">
              <span id="error-direc" class="error"></span>
            </div>
            <div class="form-group">
              <label for="telefono">Telefono Cliente</label>
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

        <!-- Tabla Prueba -->

        <div id="caca">
  <div id="ventana65">
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Nombre de Producto</th>
            <th>Descripcion del Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
          </tr>
        </thead>
        <tbody>
          <!-- Aquí se añadirían las filas de productos dinámicamente -->
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3">Subtotal</td>
            <td id="subtotal">0</td>
          </tr>
          <tr>
            <td colspan="3">IVA (19%)</td>
            <td id="iva">0</td>
          </tr>
          <tr>
            <td colspan="3">Total</td>
            <td id="total">0</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
        <!-- Carrito de Compras -->
         
    
        
        <button type="submit" class="btn btn-outline-light" id="btn6">Comprar</button>
      </form>

      </div>
    </div>


    



    <!-- Tabla Prueba -->

    



  </div>

























    <audio id="miAudio" controls autoplay style="display: none;">
      <source src="MUSIC/Grande_Colocolo.mp3" type="audio/mpeg">
      Tu navegador no soporta la reproducción de audio.
    </audio>

  </div>







  <script src="JS/newproduct.js"></script>
  
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