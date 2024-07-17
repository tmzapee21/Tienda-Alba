<?php
session_start();
include 'db.php'; // Incluye el archivo para la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="CSS/factura.css">
  <link rel="stylesheet" href="CSS/estado.css">
  <style>
    /* Estilo para el título "Boletas Rechazadas" */
    .titulo-rechazadas {
      color: #fff; /* Color blanco */
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Boletas Rechazadas</title>
</head>
<body>

<div id="contenedor">
    <div class="loader">
      <div class="leaf"></div>
      <div class="leaf"></div>
      <div class="leaf"></div>
    </div>
  </div>

  <div class="base" id="content">

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
        </div>
      </div>
    </nav>

    <div class="container mt-4">
      <h2 class="titulo-rechazadas">Boletas Rechazadas</h2> <!-- Aplicamos la clase para el título -->
      <div class="row">
        <div class="col">
          <table class="table table-striped table-hover">
            <thead class="table-dark">
              <tr>
                <th>ID Boleta</th>
                <th>Estado</th>
                <th>Descripción</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT id_boleta, estado, descripcion, fecha FROM intentos_entrega";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $row["id_boleta"] . "</td>";
                  echo "<td>Rechazado</td>"; // Estado siempre será "Rechazado" según tu lógica
                  echo "<td>" . $row["descripcion"] . "</td>";
                  echo "<td>" . $row["fecha"] . "</td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='4'>No hay boletas rechazadas.</td></tr>";
              }
              $conn->close();
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="JS/loader.js"></script>

</body>
</html>

