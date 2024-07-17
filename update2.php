<?php
include 'db.php'; // Conexión a la base de datos

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_POST['id_boleta'])) {
    $id = $_POST['id_boleta'];
    $estado = $_POST['estado'];
    $descripcion = !empty($_POST['descripcionEntrega']) ? $_POST['descripcionEntrega'] : $_POST['descripcion'];

    // Desinfecta las variables
    $id = $conn->real_escape_string($id);
    $estado = $conn->real_escape_string($estado);
    $descripcion = $conn->real_escape_string($descripcion);

    $comprobante = null; // Inicializa con null para BLOB

    if (isset($_FILES["imagenEntrega"]["name"]) && $_FILES["imagenEntrega"]["error"] == 0) {
        $rutaTemporal = $_FILES["imagenEntrega"]["tmp_name"];
        $comprobante = file_get_contents($rutaTemporal); // Lee el contenido del archivo
    } else {
        if ($_FILES["imagenEntrega"]["error"] != 4) {
            echo "File upload error: " . $_FILES["imagenEntrega"]["error"] . "<br>";
        }
    }

    // Obtener Rut_cliente de la tabla factura
    $sqlRut = "SELECT Rut_cliente FROM factura WHERE ID_Boleta = ?";
    $stmtRut = $conn->prepare($sqlRut);
    if ($stmtRut) {
        $stmtRut->bind_param("i", $id);
        $stmtRut->execute();
        $stmtRut->bind_result($rut_cliente);
        $stmtRut->fetch();
        $stmtRut->close();
    } else {
        echo "Error al preparar la consulta para obtener Rut_cliente: " . $conn->error;
        exit();
    }

    if (!empty($estado)) {
        // Verificar si la entrada ya existe en la tabla entrega
        $sqlCheck = "SELECT COUNT(*) FROM entrega WHERE ID_Boleta = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        if ($stmtCheck) {
            $stmtCheck->bind_param("i", $id);
            $stmtCheck->execute();
            $stmtCheck->store_result();
            $stmtCheck->bind_result($count);
            $stmtCheck->fetch();
            $stmtCheck->close();
        } else {
            echo "Error al preparar la consulta de verificación: " . $conn->error;
            exit();
        }

        if ($count > 0) {
            // Actualizar la tabla entrega
            if ($comprobante) {
                $sqlUpdate = "UPDATE entrega SET EstadoB = ?, Descripcion = ?, Comprobante = ?, Rut_cliente = ? WHERE ID_Boleta = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                if ($stmtUpdate) {
                    $stmtUpdate->send_long_data(2, $comprobante); // Enviar datos BLOB antes de bind_param
                    $stmtUpdate->bind_param("ssssi", $estado, $descripcion, $comprobante, $rut_cliente, $id);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();
                } else {
                    echo "Error al preparar la consulta de actualización de entrega: " . $conn->error;
                    exit();
                }
            } else {
                $sqlUpdate = "UPDATE entrega SET EstadoB = ?, Descripcion = ?, Rut_cliente = ? WHERE ID_Boleta = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                if ($stmtUpdate) {
                    $stmtUpdate->bind_param("sssi", $estado, $descripcion, $rut_cliente, $id);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();
                } else {
                    echo "Error al preparar la consulta de actualización de entrega: " . $conn->error;
                    exit();
                }
            }
        } else {
            // Insertar en la tabla entrega
            if ($comprobante) {
                $sqlInsert = "INSERT INTO entrega (ID_Boleta, EstadoB, Descripcion, Comprobante, Rut_cliente) VALUES (?, ?, ?, ?, ?)";
                $stmtInsert = $conn->prepare($sqlInsert);
                if ($stmtInsert) {
                    $stmtInsert->send_long_data(3, $comprobante); // Enviar datos BLOB antes de bind_param
                    $stmtInsert->bind_param("issss", $id, $estado, $descripcion, $comprobante, $rut_cliente);
                    $stmtInsert->execute();
                    $stmtInsert->close();
                } else {
                    echo "Error al preparar la consulta de inserción de entrega: " . $conn->error;
                    exit();
                }
            } else {
                $sqlInsert = "INSERT INTO entrega (ID_Boleta, EstadoB, Descripcion, Rut_cliente) VALUES (?, ?, ?, ?)";
                $stmtInsert = $conn->prepare($sqlInsert);
                if ($stmtInsert) {
                    $stmtInsert->bind_param("isss", $id, $estado, $descripcion, $rut_cliente);
                    $stmtInsert->execute();
                    $stmtInsert->close();
                } else {
                    echo "Error al preparar la consulta de inserción de entrega: " . $conn->error;
                    exit();
                }
            }
        }
    }

    // Mostrar los datos de entrega
    $sqlSelect = "SELECT * FROM entrega";
    $result = $conn->query($sqlSelect);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "ID_Boleta: " . $row["ID_Boleta"] . " - EstadoB: " . $row["EstadoB"] . " - Descripcion: " . $row["Descripcion"] . " - Comprobante: " . (empty($row["Comprobante"]) ? "No" : "Sí") . " - Rut_cliente: " . $row["Rut_cliente"] . "<br>";
        }
    } else {
        echo "0 resultados";
    }

    $conn->close();
    header("Location: estado.php");
    exit();
}
?>
