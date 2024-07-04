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

// Suponiendo que $row['Precio'] y $row['Cantidad'] existen y contienen los datos correctos
$subtotal = $row['Precio'] * $row['Cantidad'];
$IVA = $subtotal * 0.19; // Suponiendo que el IVA es del 19%
$total = $subtotal + $IVA;

echo '<div id="boletas2">
<div class="container">
    <p>
        <a class="btn btn-primary btn-estirado" data-toggle="collapse" href="#facturaCollapse' . $row['ID_Boleta'] . '" role="button" aria-expanded="false" aria-controls="facturaCollapse' . $row['ID_Boleta'] . '">
            <img src="IMG/Colo_a.png" alt="Logo" height="50" class="logo2"> Boleta ' . $row['ID_Boleta'] . ' - Producto: ' . $row['Nombre_Producto'] . '
            <button id="descargar' . $row['ID_Boleta'] . '">Descargar como PDF</button>
        </a>
    </p>
      <div class="collapse" id="pdf' . $row['ID_Boleta'] . '">
        <div class="collapse" id="facturaCollapse' . $row['ID_Boleta'] . '">
          <div class="card card-body" id="titulo5">
              
              <div class="logo-y-titulo">  
                  <img src="IMG/Colo_a.png" alt="Logo" height="80" class="logo">
                  <h2> ORDEN DE COMPRA </h2>
                  
              </div>
              <div class="logo-y-titulo2">
              <div id="empresa">
                <h3 id="empre">Nº ORDEN DE COMPRA: </h3>
                <p>' . $row['ID_Boleta'] . '</p>
              </div>
                <div id="empresa">
                    <h3 id="empre">EMPRESA: </h3>
                    <p>' . $row['Nombre_Empresa'] . '</p>
                </div>
                
                <div class="container3">
                <div class="cp2" >
                  <h3 id="empre4">DE: </h3>
                  <p>' . $_SESSION['username'] . '</p>
                  <p>' . $_SESSION['correo'] . '</p>
                  <p>' . $_SESSION['rut'] . '</p>
                  
                </div>
              
            <div class="cp3" >
              <div class="text-container">

              <h3 id="empre4">COBRAR A: </h3>
              <p>' . $row['usuario'] . '</p>
              <p>' . $row['Correo'] . '</p>
              <p>' . $row['Telefono'] . '</p>
              <p>' . $row['Direccion'] . '</p>
              </div>
            </div>

            </div>
            
            <div class="cp4">
                <table>
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Nombre del producto</th>
                            <th>Descripción del producto</th>
                            <th>Cantidad</th>
                            <th>Forma de pago</th>
                            <th>Subtotal</th>
                            <th>IVA</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>' . $row['Nombre_Empresa'] . '</td>
                            <td>' . $row['Nombre_Producto'] . '</td>
                            <td>' . $row['Descripcion_Producto'] . '</td>
                            <td>' . $row['Cantidad'] . '</td>
                            <td>' . $row['Pago'] . '</td>
                            <td>' . number_format($subtotal, 0, ',', '.') . ' CLP</td>
                            <td>' . number_format($IVA, 0, ',', '.') . ' CLP</td>
                            <td>' . number_format($total, 0, ',', '.') . ' CLP</td>
                        </tr>
                    </tbody>
                </table>
            </div>

              
              </div>
          </div>
          </div>
    </div>
  </div>';
}
?>

      </div>
    </div>












  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
<script src="JS/loader.js"></script>
<script>
var botones = document.querySelectorAll('[id^="descargar"]');
botones.forEach(function(boton) {
    boton.addEventListener('click', function() {
        var idBoleta = this.id.replace('descargar', '');
        var elemento = document.getElementById('facturaCollapse' + idBoleta);
        $(elemento).collapse('show');
        setTimeout(function() {
            var opt = {
                margin:       1,
                filename:     'Boleta.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 1, pagesplit: true },
                jsPDF:        { unit: 'in', format: 'a2', orientation: 'portrait' } // Cambia 'a4' a 'a5'
            };
            html2pdf().set(opt).from(elemento).save().then(function() {
                $(elemento).collapse('hide');
            });
        }, 1000);
    });
});
</script>
</body>
</html>