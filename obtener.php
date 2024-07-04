<?php
ini_set('memory_limit', '256M'); // Aumentar el límite de memoria temporalmente
include 'db.php';

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if(isset($_POST['idBoleta'])) {
    $idBoleta = intval($_POST['idBoleta']);

    $query = "SELECT ID_Entrega, ID_Boleta, EstadoB, Descripcion, Comprobante FROM entrega WHERE ID_Boleta = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $idBoleta);
        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            if ($fila = $resultado->fetch_assoc()) {
                echo "<p>ID Entrega: " . $fila['ID_Entrega'] . "</p>";
                echo "<p>ID Boleta: " . $fila['ID_Boleta'] . "</p>";
                echo "<p>Estado: " . $fila['EstadoB'] . "</p>";
                echo "<p>Descripción: " . $fila['Descripcion'] . "</p>";
                
                if (!empty($fila['Comprobante'])) {
                    // Determinar dinámicamente el tipo MIME
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $tipoMime = $finfo->buffer($fila['Comprobante']); // Obtener el tipo MIME del BLOB
                    echo "<img src='data:" . $tipoMime . ";base64," . base64_encode($fila['Comprobante']) . "' alt='Comprobante' />";
                } else {
                    echo "El comprobante está vacío.";
                }
            } else {
                echo "No se encontraron resultados.";
            }
        } else {
            echo "Error al ejecutar la consulta: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
    }
}
?>