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
              <a class="nav-link" href="tienda.php">NUEVA BOLETA</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="factura.php">BOLETAS</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="estado.php">CONFIRMACIONES</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="rechazados.php">BOLETAS RECHAZADAS</a>
            </li>
          </ul>
          <div id="UsuarioNew">
            <?php
              echo "<span class='UsuarioNew'>Bienvenido, " . $_SESSION['username'] . "</span>";
            ?>
            <button onclick="location.href='cerrar.php'" class="btn btn-outline-light">CERRAR SESIÓN</button>
          </div>
        </div>
      </div>
    </nav>

    <div>
    <div>

    <?php
include 'db.php'; // Asegúrate de tener este archivo para la conexión a la base de datos

// Consulta para contar los diferentes estados de las facturas
$sqlCount = "SELECT EstadoB, COUNT(*) as count FROM entrega WHERE EstadoB IN ('Entregado', 'Rechazado') GROUP BY EstadoB";
$resultCount = $conn->query($sqlCount);

$estadoCounts = [
    "Entregado" => 0,
    "Rechazado" => 0
];

while ($rowCount = $resultCount->fetch_assoc()) {
    $estadoCounts[$rowCount['EstadoB']] = $rowCount['count'];
}

// Mostrar los contadores en la parte superior
echo "<div class='container my-4'>";
echo "<div class='row'>";
echo "<div class='col-md-6'>";
echo "<div class='card text-white bg-success mb-3'>";
echo "<div class='card-header'>Entregado</div>";
echo "<div class='card-body'>";
echo "<h5 class='card-title'>" . $estadoCounts['Entregado'] . "</h5>";
echo "</div>";
echo "</div>";
echo "</div>";

echo "<div class='col-md-6'>";
echo "<div class='card text-white bg-danger mb-3'>";
echo "<div class='card-header'>Rechazado</div>";
echo "<div class='card-body'>";
echo "<h5 class='card-title'>" . $estadoCounts['Rechazado'] . "</h5>";
echo "</div>";
echo "</div>";
echo "</div>";

echo "</div>";
echo "</div>";

// Consulta para obtener los datos de las facturas y los productos
$sql = "SELECT f.ID_Boleta, d.Nombre_Producto, f.Rut_cliente, d.Descripcion_Producto
        FROM factura f 
        JOIN detalle_factura d ON f.ID_Boleta = d.ID_Boleta 
        ORDER BY f.ID_Boleta"; // Consulta SQL modificada

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Añade estilos para los estados
  echo "<style>.estadoEntregado { color: green; } .estadoRechazado { color: red; }</style>";

  $currentBoleta = null;
  $productos = [];

  while($row = $result->fetch_assoc()) {
    if ($currentBoleta !== $row["ID_Boleta"]) {
      if ($currentBoleta !== null) {
        // Mostrar la boleta anterior
        mostrarBoleta($currentBoleta, $productos, $conn);
        $productos = [];
      }
      $currentBoleta = $row["ID_Boleta"];
    }
    $productos[] = $row;
  }
  // Mostrar la última boleta
  if ($currentBoleta !== null) {
    mostrarBoleta($currentBoleta, $productos, $conn);
  }
} else {
  echo "0 resultados";
}
$conn->close();

function mostrarBoleta($idBoleta, $productos, $conn) {
  $sqlEstado = "SELECT EstadoB FROM entrega WHERE ID_Boleta = ?";
  $stmt = $conn->prepare($sqlEstado);
  $stmt->bind_param("i", $idBoleta);
  $stmt->execute();
  $resultEstado = $stmt->get_result();
  $rowEstado = $resultEstado->fetch_assoc();
  $estadoB = $rowEstado ? $rowEstado["EstadoB"] : "No especificado";
  $stmt->close();

  echo "<form class='formularioBoletas' action='update2.php' method='post' enctype='multipart/form-data'>";
  echo "<div class='formulario-boletas'>";
  echo "<input type='hidden' name='id_boleta' value='" . $idBoleta . "'>";
  echo "<input type='hidden' name='rut_cliente' value='" . $productos[0]["Rut_cliente"] . "'>";
  $claseColor = ($estadoB == "Entregado") ? "estadoEntregado" : (($estadoB == "Rechazado") ? "estadoRechazado" : "");
  echo "<label style='margin-right: 20px;'>ID Boleta: " . $idBoleta . ", Estado: <span class='" . $claseColor . "'>" . $estadoB . "</span></label>";
  echo "<label style='margin-bottom: 20px;'>RUT Cliente: " . $productos[0]["Rut_cliente"] . "</label>"; // Mostrar el RUT del cliente

  foreach ($productos as $producto) {
    echo "<p>Producto: " . $producto["Nombre_Producto"] . ", Descripción: " . $producto["Descripcion_Producto"] . "</p>";
  }

  // Agregar campo para el nombre del cliente cuando el estado es "Entregado"
  

  if ($estadoB == "Rechazado") {
    echo "<select class='form-control opciones' name='estado' onchange='mostrarCampos(this, this.closest(\".formularioBoletas\"))'>";
    echo "<option value='' >Elija una opción:</option>";
    echo "<option value='Entregado'>Entregado</option>";
    echo "<option value='Rechazado'>Rechazado</option>";
    echo "</select>";
    echo "<input type='text' class='form-control descripcion' name='descripcion' placeholder='Descripción del rechazo' style='display:none;'>";
    echo "<input type='text' class='form-control descripcionEntrega' name='descripcionEntrega' placeholder='Descripción de la entrega' style='display:none;'>";
    echo "<input type='file' class='form-control imagenEntrega' name='imagenEntrega' style='display:none;'>";
    echo "<button type='submit' class='btn btn-primary'>Confirmar Cambio</button>";
  } elseif ($estadoB != "Entregado") {
    echo "<select class='form-control opciones' name='estado' onchange='mostrarCampos(this, this.closest(\".formularioBoletas\"))'>";
    echo "<option value='' >Elija una opción:</option>";
    echo "<option value='Entregado'>Entregado</option>";
    echo "<option value='Rechazado'>Rechazado</option>";
    echo "</select>";
    echo "<input type='text' class='form-control descripcion' name='descripcion' placeholder='Descripción del rechazo' style='display:none;'>";
    echo "<input type='text' class='form-control nombreCliente' name='nombreCliente' placeholder='Nombre del cliente que recibe' style='display:none;'>";
    echo "<input type='text' class='form-control descripcionEntrega' name='descripcionEntrega' placeholder='Descripción de la entrega' style='display:none;'>";
    echo "<input type='file' class='form-control imagenEntrega' name='imagenEntrega' style='display:none;'>";
    echo "<button type='submit' class='btn btn-primary'>Confirmar Cambio</button>";
  } else {
    echo "<button type='button' class='btn btn-secondary' onclick='visualizarDatosAjax(" . $idBoleta . ")'>Ver Detalles</button>";
  }

  echo "</div>";
  echo "</form>";
}
?>

<div id="modalComprobante" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h1>COMPROBANTE</h1>
    <div class="contenido-modal">
      <div class="informacion"></div>
      <div class="comprobante">
        <p>Comprobante aquí</p>
      </div>
    </div>
  </div>
</div>

<script>
function visualizarDatosAjax(idBoleta) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.querySelector(".contenido-modal .informacion").innerHTML = this.responseText;
      var modal = document.getElementById("modalComprobante");
      modal.style.display = "block";

      var span = document.getElementsByClassName("close")[0];
      if (span) {
        span.addEventListener('click', function() {
          modal.style.display = "none";
        });
      }
    }
  };
  xhttp.open("POST", "obtener.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("idBoleta=" + idBoleta);
}

document.addEventListener('DOMContentLoaded', function() {
  var modal = document.getElementById("modalComprobante");
  window.addEventListener('click', function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  });
});

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.formularioBoletas').forEach(function(form) {
    form.addEventListener('submit', function(event) {
      var estadoSeleccionado = form.querySelector('.opciones').value;

      if (estadoSeleccionado === 'Entregado') {
        var descripcionEntrega = form.querySelector('.descripcionEntrega').value.trim();
        var imagenEntrega = form.querySelector('.imagenEntrega').value;
        var rutCliente = form.querySelector('input[name="rut_cliente"]').value.trim();

        if (!descripcionEntrega) {
          alert('Por favor, ingrese una descripción de entrega.');
          event.preventDefault();
          return false;
        }

        if (!imagenEntrega) {
          alert('Por favor, seleccione una imagen de entrega.');
          event.preventDefault();
          return false;
        }

        if (!rutCliente) {
          alert('No se encontró el Rut del cliente.');
          event.preventDefault();
          return false;
        }
      }

      return true;
    });
  });
});

function mostrarCampos(selectElement, formElement) {
  var descripcion = formElement.querySelector('.descripcion');
  var descripcionEntrega = formElement.querySelector('.descripcionEntrega');
  var imagenEntrega = formElement.querySelector('.imagenEntrega');
  var nombreCliente = formElement.querySelector('.nombreCliente'); // Nuevo campo para el nombre del cliente

  if (selectElement.value == 'Entregado') {
    descripcionEntrega.style.display = 'block';
    imagenEntrega.style.display = 'block';
    nombreCliente.style.display = 'block'; // Mostrar el campo del nombre del cliente
    descripcion.style.display = 'none';
  } else {
    descripcionEntrega.style.display = 'none';
    imagenEntrega.style.display = 'none';
    nombreCliente.style.display = 'none'; // Ocultar el campo del nombre del cliente
    descripcion.style.display = 'block';
  }
}

document.addEventListener('DOMContentLoaded', function () {
  const selects = document.querySelectorAll('.opciones');
  selects.forEach(select => {
    select.addEventListener('change', function () {
      const descripcion = this.nextElementSibling;
      if (this.value === 'Rechazado') {
        descripcion.style.display = 'block';
      } else {
        descripcion.style.display = 'none';
      }
    });
  });
});
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="JS/loader.js"></script>

</body>
</html>