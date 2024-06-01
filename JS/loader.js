// JS/loader.js
document.addEventListener("DOMContentLoaded", function() {
    const loader = document.getElementById('contenedor');
    const content = document.getElementById('content');

    setTimeout(function() {
        loader.style.display = 'none';
        content.style.display = 'block';
        document.body.style.overflow = 'auto'; // Enable scrolling after loader
    }, 3000); // Adjust the time as needed
});
