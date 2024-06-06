function mostrarCarrito() {
    var modal = document.getElementById('carritoModal');
    modal.style.display = 'block';
}

var span = document.getElementsByClassName('close')[0];

span.onclick = function() {
    var modal = document.getElementById('carritoModal');
    modal.style.display = 'none';
}

window.onclick = function(event) {
    var modal = document.getElementById('carritoModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

var carrito = JSON.parse(sessionStorage.getItem('carrito')) || [];

function agregarAlCarrito(producto) {
    carrito.push(producto);

    var productosCarrito = document.getElementById('productosCarrito');
    productosCarrito.innerHTML = '';

    for (var i = 0; i < carrito.length; i++) {
        productosCarrito.innerHTML += '<p>' + carrito[i] + '</p>';
    }

    var modal = document.getElementById('carritoModal');
    if (modal) {
        modal.style.display = 'block';
    }

    sessionStorage.setItem('carrito', JSON.stringify(carrito));
}

function comprar() {
    document.getElementById('formCarrito').submit();
}