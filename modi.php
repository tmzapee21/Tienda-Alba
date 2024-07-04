<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios";

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ID_Boleta'])) {
        $facturaId = filter_var($_POST['ID_Boleta'], FILTER_SANITIZE_NUMBER_INT);

        // Verifica que el ID es válido
        if (filter_var($facturaId, FILTER_VALIDATE_INT)) {
            // Conexión a la base de datos
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar conexión
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            
        } else {
            echo "ID de factura inválido.";
        }
    } else {
        echo "ID de factura no proporcionado.";
    }
} else {
    // Manejar otros métodos de solicitud o mostrar un mensaje de error
    echo "Método de solicitud no permitido";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="CSS/tienda.css">
  <link rel="stylesheet" href="CSS/factura.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
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



    <?php
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

$facturaId = isset($_POST['ID_Boleta']) ? $_POST['ID_Boleta'] : null;

if ($facturaId) {
    // Preparar la consulta SQL para seleccionar la boleta por su ID
    $stmt = $conn->prepare("SELECT * FROM factura WHERE ID_Boleta = ?");
    $stmt->bind_param("i", $facturaId); // "i" indica que el parámetro es un entero
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verifica si la consulta devuelve filas
    if ($resultado->num_rows == 1) {
        // Obtiene la fila de resultados como un array asociativo
        $row = $resultado->fetch_assoc();
        echo '
<div id="ventana55">
  <div class="form-container">
    <form method="POST" action="update.php" onsubmit="return validarFormulario()">
      <input type="hidden" name="ID_Boleta" value="' . htmlspecialchars($facturaId) . '">
      <div id="formContainer">
        <div id="leftSide">
          <div class="form-group">
            <label for="Nombre_Producto">Nombre de Producto</label>
            <input type="text" class="form-control" id="Nombre_Producto" name="Nombre_Producto" placeholder="Introduce el nombre del producto" value="' . htmlspecialchars($row['Nombre_Producto']) . '">
            <span id="error-nombreProducto" class="error"></span>
          </div>
          <div class="form-group">
            <label for="Descripcion_Producto">Descripcion del producto</label>
            <input type="text" class="form-control" id="Descripcion_Producto" name="Descripcion_Producto" placeholder="Introduce la descripción del producto" value="' . htmlspecialchars($row['Descripcion_Producto']) .'">
            <span id="error-descrip" class="error"></span>
          </div>
          <div class="form-group">
            <label for="Cantidad">Cantidad</label>
            <input type="number" class="form-control" id="Cantidad" name="Cantidad" placeholder="Introduce la cantidad" value="' . htmlspecialchars($row['Cantidad']) . '">
            <span id="error-cantidad" class="error"></span>
          </div>
          <div class="form-group">
            <label for="Precio">Precio</label>
            <input type="number" class="form-control" id="Precio" name="Precio" placeholder="Introduce el precio" value="' . htmlspecialchars($row['Precio']) . '">
            <span id="error-precio" class="error"></span>
          </div>
        </div>
        <div id="rightSide">
          <div class="form-group">
            <label for="Correo">Correo</label>
            <input type="email" class="form-control" id="Correo" name="Correo" placeholder="Introduce el correo" value="' . htmlspecialchars($row['Correo']) . '">
            <span id="error-correo" class="error"></span>
          </div>
          <div class="form-group">
            <label for="Direccion">Dirección</label>
            <input type="text" class="form-control" id="Direccion" name="Direccion" placeholder="Introduce la dirección" value="' . htmlspecialchars($row['Direccion']) . '">
            <span id="error-direc" class="error"></span>
          </div>
          <div class="form-group">
            <label for="Telefono">Telefono</label>
            <input type="tel" class="form-control" id="Telefono" name="Telefono" placeholder="Introduce el teléfono" value="' . htmlspecialchars($row['Telefono']) . '">
            <span id="error-tele" class="error"></span>
          </div>
          <div class="form-group">
            <label for="Pago">Pago</label>
            <select class="form-control" id="Pago" name="Pago">
            <option value="">Elija una opción:</option>
            <option value="Presencial" ' . (htmlspecialchars($row['Pago']) == 'Presencial' ? 'selected' : '') . '>Presencial</option>
            <option value="Transferencia" ' . (htmlspecialchars($row['Pago']) == 'Transferencia' ? 'selected' : '') . '>Transferencia</option>
            </select>
            <span id="error-pago" class="error"></span>
            </div>
        </div>
      </div>
      <button type="submit" class="btn btn-outline-light" id="btn6">Modificar</button>
    </form>
  </div>
</div>
';

    } else {
        echo "No se encontró la boleta con el ID especificado.";
    }
} else {
    echo "ID de boleta no proporcionado.";
}
$conn->close(); // Es buena práctica cerrar la conexión cuando ya no es necesaria
?>





    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script src="JS/loader.js"></script>

</body>
</html>
