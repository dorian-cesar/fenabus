<?php

// Configuración de la base de datos
include 'config.php';

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para limpiar los datos de entrada
function limpiarDatos($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


// Manejar la solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener los datos del cuerpo de la solicitud (JSON)
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data === null) {
        // Manejar error si el JSON no es válido
        http_response_code(400); // Bad Request
        echo json_encode(array("error" => "Datos JSON inválidos"));
        exit(); 
    }


    $rut = limpiarDatos($data['rut']);
    $id_card=limpiarDatos($data['id_card']);
    $fechaEvento = limpiarDatos($data['fechaEvento']);
    $horaEvento = limpiarDatos($data['horaEvento']);
    $tipoEvento = limpiarDatos($data['tipoEvento']);
    $tipoTransaccion = limpiarDatos($data['tipoTransaccion']);
    $lat = limpiarDatos($data['lat']);
    $long = limpiarDatos($data['long']);
    $codAuth = limpiarDatos($data['codAuth']);
    $correlativo = limpiarDatos($data['correlativo']);


    // Validar datos (puedes agregar más validaciones)
    if (empty($rut) || empty($id_card) || empty($fechaEvento) || empty($horaEvento) || empty($tipoEvento) || empty($tipoTransaccion) || empty($lat) || empty($long) || empty($codAuth) || empty($correlativo)) {
        http_response_code(400); // Bad Request
        echo json_encode(array("error" => "Todos los campos son obligatorios"));
        exit();
    }

    // Preparar la consulta SQL
    
    $sql = "INSERT INTO eventos (rut,id_card, fechaEvento, horaEvento, tipoEvento, tipoTransaccion, lat, lng, codAuth, correlativo) 
            VALUES ('$rut','$id_card', '$fechaEvento', '$horaEvento', '$tipoEvento', '$tipoTransaccion', '$lat', '$long', '$codAuth', '$correlativo')";

    if ($conn->query($sql) === TRUE) {
        http_response_code(201); // Created
        echo json_encode(array("mensaje" => "Evento guardado correctamente", "id" => $conn->insert_id)); // Incluir el ID del registro insertado
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("error" => "Error al guardar el evento: " . $conn->error));
    }

} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("error" => "Método no permitido"));
}

$conn->close();

?>