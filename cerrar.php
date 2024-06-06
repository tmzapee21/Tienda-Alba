<?php

// Destruye la sesión
session_destroy();

// Redirige al usuario a index.html
header('Location: index.html');
exit;
?>