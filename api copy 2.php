<?php
require 'config.php';

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
    
    try {
        $stmt = $pdo->prepare("INSERT INTO eventos (rut, id_card, fechaEvento, horaEvento, tipoEvento, tipoTransaccion, lat, lng, codAuth, correlativo) 
                               VALUES (:rut, :id_card, :fechaEvento, :horaEvento, :tipoEvento, :tipoTransaccion, :lat, :lng, :codAuth, :correlativo)");
        
        $stmt->execute([
            ':rut' => $data['rut'],
            ':id_card' => $data['id_card'],
            ':fechaEvento' => $data['fechaEvento'],
            ':horaEvento' => $data['horaEvento'],
            ':tipoEvento' => $data['tipoEvento'],
            ':tipoTransaccion' => $data['tipoTransaccion'],
            ':lat' => $data['lat'],
            ':lng' => $data['lng'],
            ':codAuth' => $data['codAuth'],
            ':correlativo' => $data['correlativo'],
        ]);
        
        $lastId = $pdo->lastInsertId();
        
        http_response_code(201);
        echo json_encode(['message' => 'Datos guardados correctamente', 'id' => $lastId]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al guardar los datos', 'details' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
