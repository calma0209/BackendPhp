<?php
// Datos de prueba para actualizar usuario
$id_usuario = 6; // Cambia esto por un ID real de usuario en tu BD
$api_url = "http://localhost:8080/api/usuarios/$id_usuario";

// Simulamos la actualización de perfil con nueva contraseña
$data = [
    "id_usuario" => $id_usuario,
    "nombre_usuario" => "Alonso",
    "email" => "alonso12@email.com",
    "contraseña_actual" => "nuevo",  // Debe coincidir con la actual en BD
    "contraseña" => "nuevaClave123"   // Nueva contraseña
];

// Convertir datos a JSON
$data_json = json_encode($data);

// Configurar cURL para enviar la solicitud PUT a la API
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

// Ejecutar la solicitud
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Mostrar respuesta de la API
echo "Código de respuesta HTTP: $http_code <br>";
echo "Respuesta de la API: <br>";
echo "<pre>" . print_r(json_decode($response, true), true) . "</pre>";
?>
