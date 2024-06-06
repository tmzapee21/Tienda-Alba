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
                    <a class="nav-link" href="#">BOLETA</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">CARRITO</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="tienda.html">CATALOGO</a>
                </li>

            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-3" type="search" placeholder="Buscar" aria-label="Search">
                <button class="btn btn-outline-light" type="submit">Buscar</button>
            </form>
            <div id="UsuarioNew">
            
    <?php
    echo "<span class='UsuarioNew'>Bienvenido, " . $_SESSION['username'] . "</span>";
    ?>
    <button onclick="location.href='cerrar.php'" class="btn btn-outline-light" >CERRAR SESIÓN</button>
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
                      <p class="card-text">Precio: $ <?php echo $producto["Precio"]; ?></p>
                      <p class="card-text">Stock: <?php echo $producto["Stock"]; ?></p>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          









        <audio id="miAudio" controls autoplay style="display: none;">
            <source src="MUSIC/Grande_Colocolo.mp3" type="audio/mpeg">
            Tu navegador no soporta la reproducción de audio.
        </audio>

    </div>


    <script src="carrito.js"></script>
    <script src="JS/musica.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="JS/loader.js"></script>
</body>

</html>