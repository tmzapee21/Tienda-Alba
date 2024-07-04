<?php
ini_set('memory_limit', '256M'); // Aumentar el límite de memoria temporalmente
include 'db.php';

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_POST['idBoleta'])) {
    $idBoleta = intval($_POST['idBoleta']);

    $query = "SELECT ID_Entrega, ID_Boleta, EstadoB, Descripcion, Comprobante FROM entrega WHERE ID_Boleta = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $idBoleta);
        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            if ($fila = $resultado->fetch_assoc()) {
                if (!empty($fila['Comprobante'])) {
                    // Asumiendo que 'Comprobante' ahora contiene la ruta de la imagen
                    $rutaImagen = $fila['Comprobante'];
                    if (file_exists($rutaImagen)) {
                        echo "<div>";
                        echo "<p>ID Entrega: " . htmlspecialchars($fila['ID_Entrega']) . "</p>";
                        echo "<p>ID Boleta: " . htmlspecialchars($fila['ID_Boleta']) . "</p>";
                        echo "<p>Estado: " . htmlspecialchars($fila['EstadoB']) . "</p>";
                        echo "<p>Descripción: " . htmlspecialchars($fila['Descripcion']) . "</p>";
                        // Mostrar la imagen directamente usando su ruta
                        echo "<img src='" . htmlspecialchars($rutaImagen) . "' alt='Comprobante' />";
                        echo "</div>";
                    } else {
                        echo "<p>La imagen no se encuentra o la ruta es incorrecta.</p>";
                    }
                } else {
                    echo "<p>El comprobante está vacío.</p>";
                }
            } else {
                echo "<p>No se encontraron resultados.</p>";
            }
        } else {
            echo "<p>Error al ejecutar la consulta: " . htmlspecialchars($stmt->error) . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Error al preparar la consulta: " . htmlspecialchars($conn->error) . "</p>";
    }

    $conn->close();
} else {
    echo "<p>ID de Boleta no proporcionado.</p>";
}
?>