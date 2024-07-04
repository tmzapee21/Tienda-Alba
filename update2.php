<?php
include 'db.php'; // Conexión a la base de datos

if (isset($_POST['id_boleta'])) {
    $id = $_POST['id_boleta'];
    $estado = $_POST['estado'];
    $descripcion = !empty($_POST['descripcionEntrega']) ? $_POST['descripcionEntrega'] : $_POST['descripcion'];

    // Desinfecta las variables
    $id = $conn->real_escape_string($id);
    $estado = $conn->real_escape_string($estado);
    $descripcion = $conn->real_escape_string($descripcion);

    $rutaImagen = ''; // Inicializa con un valor vacío

    if (isset($_FILES["imagenEntrega"]["name"]) && $_FILES["imagenEntrega"]["error"] == 0) {
        $nombreArchivo = time() . '_' . basename($_FILES["imagenEntrega"]["name"]);
        $rutaTemporal = $_FILES["imagenEntrega"]["tmp_name"];
        $rutaDestino = $directorioDestino . $nombreArchivo;
        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
            $rutaImagen = $rutaDestino; // Ruta de la imagen para guardar en DB
        }
    }
    
    if (!empty($estado)) {
        $sql = "UPDATE factura SET EstadoB = ? WHERE ID_Boleta = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("si", $estado, $id);
            $stmt->execute();
    
            $sqlCheck = "SELECT COUNT(*) FROM entrega WHERE ID_Boleta = ?";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bind_param("i", $id);
            $stmtCheck->execute();
            $stmtCheck->store_result();
            $stmtCheck->bind_result($count);
            $stmtCheck->fetch();
    
            if ($count > 0) {
                // Actualiza incluyendo la ruta de la imagen si está disponible
                $sqlUpdate = "UPDATE entrega SET EstadoB = ?, Descripcion = ?, Comprobante = ? WHERE ID_Boleta = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                if ($stmtUpdate) {
                    $stmtUpdate->bind_param("sssi", $estado, $descripcion, $rutaImagen, $id);
                    $stmtUpdate->execute();
                }
            } else {
                // Inserta incluyendo la ruta de la imagen si está disponible
                $sqlInsert = "INSERT INTO entrega (ID_Boleta, EstadoB, Descripcion, Comprobante) VALUES (?, ?, ?, ?)";
                $stmtInsert = $conn->prepare($sqlInsert);
                if ($stmtInsert) {
                    $stmtInsert->bind_param("isss", $id, $estado, $descripcion, $rutaImagen);
                    $stmtInsert->execute();
                }
            }
        }
    }

    // Mostrar los datos de entrega
    $sqlSelect = "SELECT * FROM entrega";
    $result = $conn->query($sqlSelect);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "ID_Boleta: " . $row["ID_Boleta"]. " - EstadoB: " . $row["EstadoB"]. " - Descripcion: " . $row["Descripcion"] . " - Comprobante: " . $row["Comprobante"] . "<br>";
        }
    } else {
        echo "0 resultados";
    }
}

$conn->close();
header("Location: estado.php");
exit();
?>