<?php
// Filename: actualizarPerfil.php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "error" => "Método no permitido"]);
    exit();
}

// Leer datos JSON que llegan de Profile.jsx
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_usuario'], $data['nombre_usuario'])) {
    echo json_encode(["success" => false, "error" => "Faltan datos obligatorios"]);
    exit();
}

$id_usuario = trim($data['id_usuario']);
$nombre_usuario = trim($data['nombre_usuario']);
$email = isset($data['email']) ? trim($data['email']) : null;

// Contraseñas opcionales para cambio
$contraseña_actual = isset($data['contraseña_actual']) ? trim($data['contraseña_actual']) : null;
$contraseña_nueva  = isset($data['contraseña'])       ? trim($data['contraseña'])       : null;

// Construir el payload que la API de Spring Boot espera
$payload = [
    "nombre_usuario" => $nombre_usuario,
    "email" => $email,
    // En el usuarioService, se llama getContraseña_actual() y getContraseña()
];

// Solo incluimos si vienen
if ($contraseña_actual) {
    $payload["contraseña_actual"] = $contraseña_actual;
}
if ($contraseña_nueva) {
    $payload["contraseña"] = $contraseña_nueva;
}

// PUT a /api/usuarios/{id}
$api_url = "http://localhost:8080/api/usuarios/" . $id_usuario;
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    echo json_encode(["success" => true, "message" => "Perfil actualizado"]);
} elseif ($http_code == 401) {
    echo json_encode(["success" => false, "error" => "Contraseña actual incorrecta"]);
} else {
    echo json_encode(["success" => false, "error" => "Error desconocido al actualizar"]);
}
?>
