document.addEventListener("DOMContentLoaded", function() {
    const loader = document.getElementById('contenedor');
    const content = document.getElementById('content');

    setTimeout(function() {
        loader.style.display = 'none';
        content.style.display = 'block';
    }, 3000); // Ajusta el tiempo (en milisegundos) según tus necesidades
});
