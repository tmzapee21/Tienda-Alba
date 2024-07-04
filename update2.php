<?php
include 'db.php'; // Conexión a la base de datos

if (isset($_POST['id_boleta'])) {
    $id = $_POST['id_boleta'];
    $estado = $_POST['estado'];
    $descripcion = $_POST['descripcion']; // Captura la descripción del formulario

    // Asegúrate de validar y desinfectar las variables aquí
    $id = $conn->real_escape_string($id);
    $estado = $conn->real_escape_string($estado);
    $descripcion = $conn->real_escape_string($descripcion); // Desinfecta la descripción

    if (!empty($estado)) { // Solo actualiza si se ha seleccionado un estado
        // Actualiza factura
        $sql = "UPDATE factura SET EstadoB = ? WHERE ID_Boleta = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("si", $estado, $id);
            $stmt->execute();

            // Verifica si ID_Boleta existe en entrega
            $sqlCheck = "SELECT COUNT(*) FROM entrega WHERE ID_Boleta = ?";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bind_param("i", $id);
            $stmtCheck->execute();
            $stmtCheck->store_result();
            $stmtCheck->bind_result($count);
            $stmtCheck->fetch();

            if ($count > 0) {
                // ID_Boleta existe, actualiza EstadoB y Descripcion
                $sqlUpdate = "UPDATE entrega SET EstadoB = ?, Descripcion = ? WHERE ID_Boleta = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                if ($stmtUpdate) {
                    $stmtUpdate->bind_param("ssi", $estado, $descripcion, $id);
                    $stmtUpdate->execute();
                }
            } else {
                // ID_Boleta no existe, inserta EstadoB y Descripcion
                $sqlInsert = "INSERT INTO entrega (ID_Boleta, EstadoB, Descripcion) VALUES (?, ?, ?)";
                $stmtInsert = $conn->prepare($sqlInsert);
                if ($stmtInsert) {
                    $stmtInsert->bind_param("iss", $id, $estado, $descripcion);
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
            echo "ID_Boleta: " . $row["ID_Boleta"]. " - EstadoB: " . $row["EstadoB"]. " - Descripcion: " . $row["Descripcion"] . "<br>";
        }
    } else {
        echo "0 resultados";
    }
}

$conn->close();
header("Location: estado.php"); // Redirige de nuevo a la página de estado
exit();
?>