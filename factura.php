<?php

session_start();

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
              <a class="nav-link" href="tienda.php">TIENDA</a>
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

    <?php
// Conexión a la base de datos
$host = 'localhost'; // Cambia esto a tu host
$db   = 'usuarios'; // Cambia esto a tu nombre de base de datos
$user = 'root'; // Cambia esto a tu usuario
$pass = ''; // Cambia esto a tu contraseña
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

// Consulta para obtener los datos de la tabla "factura"
$sql = "SELECT * FROM factura WHERE usuario = :usuario";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':usuario', $_SESSION['username']);
$stmt->execute();

while ($row = $stmt->fetch())
{
  echo '<div id="boletas2">
  <div class="container">
      <p>
          <a class="btn btn-primary btn-estirado" data-toggle="collapse" href="#facturaCollapse' . $row['ID_Boleta'] . '" role="button" aria-expanded="false" aria-controls="facturaCollapse' . $row['ID_Boleta'] . '">
              <img src="IMG/Colo_a.png" alt="Logo" height="50" class="logo2"> Boleta ' . $row['ID_Boleta'] . ' - Producto: ' . $row['Nombre_Producto'] . '
          </a>
      </p>
      <div class="collapse" id="facturaCollapse' . $row['ID_Boleta'] . '">
          <div class="card card-body">
              <img src="IMG/Colo_a.png" alt="Logo" height="10" class="logo">
              <h5> Nº Factura #' . $row['ID_Boleta'] . '</h5>
              <p>Empresa: ' . $row['Nombre_Empresa'] . '</p>
              <p>Producto: ' . $row['Nombre_Producto'] . '</p>
              <!-- ... el resto de los datos de la factura ... -->
          </div>
      </div>
  </div>
</div>';
}
?>


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