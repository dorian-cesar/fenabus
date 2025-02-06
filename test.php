<?php
$url = 'https://masgps-bi.wit.la/fenabus/api.php'; // Cambia esto a la URL de tu API

$data = [
    'rut' => '12345678-9',
    'id_card' => 'ABC123',
    'fechaEvento' => '2024-02-06',
    'horaEvento' => '12:34:56',
    'tipoEvento' => 1,
    'tipoTransaccion' => 2,
    'lat' => '-33.4489',
    'lng' => '-70.6693',
    'codAuth' => 'XYZ789',
    'correlativo' => 1001
];

$options = [
    'http' => [
        'header'  => "Content-Type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
        'timeout' => 10 // Tiempo máximo de espera en segundos
    ]
];

$context = stream_context_create($options);
$response = @file_get_contents($url, false, $context);

if ($response === false) {
    echo "Error al conectar con la API.";
} else {
    $httpCode = explode(' ', $http_response_header[0])[1] ?? '500';
    $result = json_decode($response, true);
    
    if ($httpCode == 201) {
        echo "Datos guardados correctamente. ID: " . $result['id'];
    } else {
        echo "Error ({$httpCode}): " . ($result['error'] ?? 'Desconocido');
    }
}
?>
