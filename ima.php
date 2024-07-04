<?php
include 'db.php'; // Asegúrate de que este archivo contiene la conexión a tu base de datos

$idBoleta = $_GET['idBoleta'] ?? ''; // Obtén el ID de la boleta desde la URL o ajusta según sea necesario

if ($idBoleta) {
    $query = "SELECT rutaImagen FROM entrega WHERE ID_Boleta = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $idBoleta);
        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            if ($fila = $resultado->fetch_assoc()) {
                $rutaImagen = $fila['rutaImagen'];
                if (file_exists($rutaImagen)) {
                    $tipoMime = mime_content_type($rutaImagen); // Obtiene el tipo MIME del archivo
                    header("Content-Type: $tipoMime");
                    readfile($rutaImagen); // Lee y muestra el archivo de imagen
                    exit;
                } else {
                    echo "La imagen no existe.";
                }
            } else {
                echo "No se encontró la imagen para la ID de boleta proporcionada.";
            }
        } else {
            echo "Error al ejecutar la consulta.";
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta.";
    }
} else {
    echo "ID de Boleta no proporcionado.";
}

$conn->close();
?>