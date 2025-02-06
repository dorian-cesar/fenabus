<?php
header('Content-Type: application/json');
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $requiredFields = ['rut', 'id_card', 'fechaEvento', 'horaEvento', 'tipoEvento', 'tipoTransaccion', 'lat', 'lng', 'codAuth', 'correlativo'];
    
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            http_response_code(400);
            echo json_encode(['error' => "El campo $field es obligatorio"]);
            exit;
        }
    }
    
    $conn = mysqli_connect($host, $username, $password, $dbname);
    if (!$conn) {
        http_response_code(500);
        echo json_encode(['error' => 'Error de conexión a la base de datos']);
        exit;
    }
    
    $rut = mysqli_real_escape_string($conn, $data['rut']);
    $id_card = mysqli_real_escape_string($conn, $data['id_card']);
    $fechaEvento = mysqli_real_escape_string($conn, $data['fechaEvento']);
    $horaEvento = mysqli_real_escape_string($conn, $data['horaEvento']);
    $tipoEvento = (int) $data['tipoEvento'];
    $tipoTransaccion = (int) $data['tipoTransaccion'];
    $lat = mysqli_real_escape_string($conn, $data['lat']);
    $lng = mysqli_real_escape_string($conn, $data['lng']);
    $codAuth = mysqli_real_escape_string($conn, $data['codAuth']);
    $correlativo = (int) $data['correlativo'];
    
    $query = "INSERT INTO eventos (rut, id_card, fechaEvento, horaEvento, tipoEvento, tipoTransaccion, lat, lng, codAuth, correlativo) 
              VALUES ('$rut', '$id_card', '$fechaEvento', '$horaEvento', $tipoEvento, $tipoTransaccion, '$lat', '$lng', '$codAuth', $correlativo)";
    
    if (mysqli_query($conn, $query)) {
        $lastId = mysqli_insert_id($conn);
        http_response_code(201);
        echo json_encode(['message' => 'Datos guardados correctamente', 'id' => $lastId]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al guardar los datos', 'details' => mysqli_error($conn)]);
    }
    
    mysqli_close($conn);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
