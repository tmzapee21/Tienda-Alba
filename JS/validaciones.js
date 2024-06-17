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
    var correo = $('#correo').val();
    var errorcorreo = $('#error-correo');
    var direccion = $('#direccion').val();
    var errordirec = $('#error-direc');
    var telefono = $('#telefono').val();
    var errortele = $('#error-tele');
    var opciones = $('#opciones').val();
    var errorpago = $('#error-pago');


    if(!nombreProducto) {
      errorNombreProducto.text("Este campo es obligatorio.");
      return;
    } else {
      errorNombreProducto.text("");
    }

    if(!descripcionProducto) {
      errordesc.text("Este campo es obligatorio.");
      return;
    } else {
      errordesc.text("");
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

    

    if(cantidad <= 0) {
      errorcantidad.text("Por favor, introduce una cantidad válida.");
      return;
    } else {
      errorcantidad.text("");
    }

    

    if(precio <= 0) {
      errorprecio.text("Por favor, introduce un precio válido.");
      return;
    } else {
      errorprecio.text("");
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

    if( !nombreProducto || !descripcionProducto || !cantidad || !precio || !correo || !direccion || !telefono || !opciones) {
      alert("Todos los campos son obligatorios.");
      return;
    }

    $.ajax({
      type: 'POST',
      url: 'tienda.php',
      data: $(this).serialize(),
      success: function(response) {
        if(response === 'success') {
          window.location.href = 'factura.php';
        } else {
          // Aquí puedes manejar los errores devueltos por el servidor
        }
      }
    });
  });
});