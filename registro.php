<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "error" => "Método no permitido"]);
    exit();
}

// Leer datos JSON enviados desde React
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['nombre_usuario'], $data['email'], $data['contraseña'], $data['rol'])) {
    echo json_encode(["success" => false, "error" => "Faltan datos"]);
    exit();
}

$nombre_usuario = trim($data['nombre_usuario']);
$email = trim($data['email']);
$contraseña = trim($data['contraseña']);
$rol = trim($data['rol']); // 'usuario' o 'admin'

// Preparar datos para enviar a la API de Spring Boot
$api_url = "http://localhost:8080/api/usuarios"; 
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "nombre_usuario" => $nombre_usuario,
    "email" => $email,
    "contraseña" => $contraseña, // ¿Está encriptada en Spring Boot?
    "rol" => $rol
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 201) {
    echo json_encode(["success" => true, "message" => "Registro exitoso"]);
} elseif ($http_code == 400) {
    echo json_encode(["success" => false, "error" => "Datos inválidos o email ya registrado"]);
} else {
    echo json_encode(["success" => false, "error" => "Error desconocido. Inténtalo más tarde"]);
}
?>
