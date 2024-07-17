function obtenerProductosCarrito() {
  // Convertir el array de productos del carrito a una cadena JSON
  return JSON.stringify(carrito);
}

$(document).ready(function() {
  $('form').on('submit', function(e) {
    e.preventDefault();

    var nombreProducto = $('#nombreProducto').val();
    var errorNombreProducto = $('#error-nombreProducto');
    var descripcionProducto = $('#descripcionProducto').val();
    var errordesc = $('#error-descrip');
    var cantidad = $('#cantidad').val();
    var errorcantidad = $('#error-cantidad');
    var precio = $('#precio').val();
    var errorprecio = $('#error-precio');
    var rut = $.trim($('#rut').val());
    var errorrut = $('#error-rut');
    var correo = $('#correo').val();
    var errorcorreo = $('#error-correo');
    var direccion = $('#direccion').val();
    var errordirec = $('#error-direc');
    var telefono = $('#telefono').val();
    var errortele = $('#error-tele');
    var opciones = $('#opciones').val();
    var errorpago = $('#error-pago');


    if(rut.length <= 5 || !rut.includes('-')) {
      errorrut.text("El RUT Invalido debe contener más de 5 caracteres y un guion.");
      return;
    } else {
      errorrut.text("");
    }



    


    if(!/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/.test(correo)) {
      errorcorreo.text("Por favor, introduce un correo válido.");
      return;
    } else {
      errorcorreo.text("");
    }

    if(!direccion) {
      errordirec.text("Este campo es obligatorio.");
      return;
    } else {
      errordirec.text("");
    }

    


    if(!/^[0-9]{9}$/.test(telefono)) {
      errortele.text("Por favor, introduce un número de teléfono válido.");
      return;
    } else {
      errortele.text("");
    }

    if(opciones === "") {
      errorpago.text("Por favor, elija una opción de pago.");
      return;
    } else {
      errorpago.text("");
    }

    
    var productosCarrito = obtenerProductosCarrito();

    $.ajax({
      type: 'POST',
      url: 'Pcompra.php',
      data: $(this).serialize() + "&productosCarrito=" + encodeURIComponent(productosCarrito),
      success: function(response) {
        if(response === 'success') {
          window.location.href = 'factura.php';
        } else {
          alert("Error: " + response); // Mostrar el error devuelto por el servidor
        }
      },
      error: function(xhr, status, error) {
        alert("AJAX error: " + error); // Mostrar error de AJAX
      }
    });
  });
});