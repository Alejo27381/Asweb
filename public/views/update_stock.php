<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $carrito = $input['carrito'];

    // Conexión a la base de datos
    $mysqli = new mysqli("localhost:3306", "root", "", "myweb");

    if ($mysqli->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Conexión a la base de datos fallida']));
    }

    // Procesar el carrito y actualizar el stock
    foreach ($carrito as $item) {
        $codigo = $mysqli->real_escape_string($item['codigo']);
        $cantidad = (int)$item['cantidad'];

        // Actualizar el stock en la base de datos
        $sql = "UPDATE productos SET stock = stock - $cantidad WHERE codigo = '$codigo'";
        if (!$mysqli->query($sql)) {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el stock']);
            exit;
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Stock actualizado con éxito']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
?>
