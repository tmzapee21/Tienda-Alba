// Espera a que la página esté completamente cargada
window.addEventListener('load', function () {
    // Selecciona el elemento de audio
    var audio = document.getElementById('miAudio');
    // Intenta iniciar la reproducción
    audio.play().catch(function (error) {
        console.log('La reproducción automática fue bloqueada:', error);
    });
});