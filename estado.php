<?php
session_start();



?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="CSS/factura.css">
  <link rel="stylesheet" href="CSS/estado.css">
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


    <div>
    <div>

    <?php
include 'db.php'; // Asegúrate de tener este archivo para la conexión a la base de datos

$sql = "SELECT ID_Boleta, Nombre_Producto, Descripcion_Producto FROM factura"; // Ajusta los nombres de las columnas y la tabla según tu base de datos

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
  // Iterar sobre cada fila de resultados
  while($row = $result->fetch_assoc()) {
    // Consulta para obtener EstadoB para la ID_Boleta actual
    $sqlEstado = "SELECT EstadoB FROM entrega WHERE ID_Boleta = ?";
    $stmt = $conn->prepare($sqlEstado);
    $stmt->bind_param("i", $row["ID_Boleta"]);
    $stmt->execute();
    $resultEstado = $stmt->get_result();
    $rowEstado = $resultEstado->fetch_assoc();
    $estadoB = $rowEstado ? $rowEstado["EstadoB"] : "No especificado"; // Asegúrate de manejar el caso de que no haya un EstadoB

    // Inicio del formulario para cada boleta
    echo "<form class='formularioBoletas' action='update2.php' method='post'>";
    echo "<div class='formulario-boletas'>";
      echo "<div class='form-group'>";
      // Input oculto para el ID de la boleta
      echo "<input type='hidden' name='id_boleta' value='" . $row["ID_Boleta"] . "'>";
      // Información de la boleta, incluyendo EstadoB
      echo "<label>ID Boleta: " . $row["ID_Boleta"] . ", Producto: " . $row["Nombre_Producto"] . ", Descripción: " . $row["Descripcion_Producto"] . ", Estado: " . $estadoB . "</label>";
      // Selector de estado
      echo "<select class='form-control opciones' name='estado'>";
      echo "<option value=''>Elija una opción:</option>";
      echo "<option value='Entregado'>Entregado</option>";
      echo "<option value='Rechazado'>Rechazado</option>";
      echo "</select>";
      // Campo de texto para la descripción del rechazo
      echo "<input type='text' class='form-control descripcion' name='descripcion' placeholder='Descripción del rechazo' style='display:none;'>";
      echo "</div>";
    // Botón de envío para cada formulario
    echo "<button type='submit' class='btn btn-primary'>Confirmar Cambio</button>";
    echo "</div>";
    echo "</form>"; // Cierre del formulario
  }
    
} else {
  echo "0 resultados";
}
$conn->close();
?>


    

    </div>
    </div>


    <script>
  // Selecciona todos los formularios con la clase 'formularioBoletas' en lugar de un ID único
  document.querySelectorAll('.formularioBoletas').forEach(function(form) {
    form.addEventListener('submit', function(event) {
      var estados = form.querySelectorAll('.opciones');
      var descripciones = form.querySelectorAll('.descripcion');
      var formValido = true;

      estados.forEach(function(estado, index) {
          // Verifica si no se ha seleccionado una opción
          if (estado.value === '') {
              alert('Debe elegir una opción para cada boleta.');
              estado.focus(); // Coloca el foco en el select que no tiene una opción elegida
              formValido = false;
              return false; // Sale del bucle actual
          }

          // Aplica la validación de descripción solo para rechazos
          if (estado.value === 'Rechazado' && descripciones[index].value.trim() === '') {
              alert('Debe proporcionar una descripción para los rechazos.');
              descripciones[index].style.display = 'block'; // Asegúrate de que el campo de descripción sea visible
              descripciones[index].focus(); // Coloca el foco en el campo de descripción vacío
              formValido = false;
          }
      });

      if (!formValido) {
          event.preventDefault(); // Detiene el envío del formulario
      }
    });
  });
</script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const selects = document.querySelectorAll('.opciones');

        selects.forEach(select => {
          select.addEventListener('change', function () {
            const descripcion = this.nextElementSibling; // Asume que el input de descripción es el siguiente elemento
            if (this.value === 'Rechazado') {
              descripcion.style.display = 'block';
            } else {
              descripcion.style.display = 'none';
            }
          });
        });
      });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
<script src="JS/loader.js"></script>



    </body>
    </html>