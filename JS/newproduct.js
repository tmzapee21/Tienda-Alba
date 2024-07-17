var carrito = [];

function agregarProductoAlCarrito() {
    // Obtener valores de los inputs
    var nombreProducto = document.getElementById('nombreProducto').value;
    var descripcionProducto = document.getElementById('descripcionProducto').value;
    var cantidad = document.getElementById('cantidad').value;
    var precio = parseFloat(document.getElementById('precio').value);

    // Validar que los campos no estén vacíos
    if (!nombreProducto || !descripcionProducto || !cantidad || isNaN(precio)) {
        alert('Por favor, rellene todos los campos del producto.');
        return;
    }

    // Agregar producto al array del carrito
    carrito.push({
        nombreProducto: nombreProducto,
        descripcionProducto: descripcionProducto,
        cantidad: cantidad,
        precio: precio
    });


    // Formatear precio como moneda chilena
    var precioFormatoChileno = precio.toLocaleString('es-CL', { style: 'currency', currency: 'CLP' });

    // Crear nueva fila en la tabla
    var tabla = document.querySelector('#caca .table tbody');
    var nuevaFila = tabla.insertRow();
    var celdaNombre = nuevaFila.insertCell(0);
    var celdaDescripcion = nuevaFila.insertCell(1);
    var celdaCantidad = nuevaFila.insertCell(2);
    var celdaPrecio = nuevaFila.insertCell(3);
    var celdaEliminar = nuevaFila.insertCell(4); // Celda para el botón de eliminar

    // Asignar los valores a las celdas
    celdaNombre.textContent = nombreProducto;
    celdaDescripcion.textContent = descripcionProducto;
    celdaCantidad.textContent = cantidad;
    celdaPrecio.textContent = precioFormatoChileno;
    celdaPrecio.setAttribute('data-precio', precio);

    // Crear y asignar el botón de eliminar
    var btnEliminar = document.createElement('button');
    btnEliminar.textContent = 'Eliminar';
    btnEliminar.className = 'btn-eliminar';
    btnEliminar.onclick = function() {
        // Eliminar la fila del botón que fue clickeado
        nuevaFila.parentNode.removeChild(nuevaFila);
        actualizarTotales();
    };
    celdaEliminar.appendChild(btnEliminar);

    // Actualizar totales
    actualizarTotales();

    // Vaciar los inputs
    document.getElementById('nombreProducto').value = '';
    document.getElementById('descripcionProducto').value = '';
    document.getElementById('cantidad').value = '';
    document.getElementById('precio').value = '';
}

function actualizarTotales() {
    var tabla = document.querySelector('#caca .table tbody');
    var filas = tabla.rows;

    var subtotal = 0;
    for (var i = 0; i < filas.length; i++) {
        var precio = parseFloat(filas[i].cells[3].getAttribute('data-precio'));
        var cantidad = parseInt(filas[i].cells[2].textContent);
        subtotal += precio * cantidad;
    }

    var iva = subtotal * 0.19;
    var total = subtotal + iva;

    // Formatear como moneda chilena
    document.getElementById('subtotal').textContent = subtotal.toLocaleString('es-CL', { style: 'currency', currency: 'CLP' });
    document.getElementById('iva').textContent = iva.toLocaleString('es-CL', { style: 'currency', currency: 'CLP' });
    document.getElementById('total').textContent = total.toLocaleString('es-CL', { style: 'currency', currency: 'CLP' });
}

