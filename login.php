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

// Recibir datos desde React
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['contraseña'])) {
    echo json_encode(["success" => false, "error" => "Faltan datos"]);
    exit();
}

$email = trim($data['email']);
$contraseña = trim($data['contraseña']);

// Conectarse a la API de Spring Boot
$api_url = "http://localhost:8080/api/usuarios/login";
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["email" => $email, "contraseña" => $contraseña]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$usuario = json_decode($response, true);

if ($http_code == 200 && isset($usuario["id_usuario"])) {
    $_SESSION["id_usuario"] = $usuario["id_usuario"];
    $_SESSION["nombre_usuario"] = $usuario["nombre_usuario"];
    $_SESSION["email"] = $usuario["email"];
    $_SESSION["rol"] = $usuario["rol"]; // Si usas roles

    // Opcional: Guardar token en cookie por 7 días
    setcookie("user_session", session_id(), time() + (7 * 24 * 60 * 60), "/");

    echo json_encode(["success" => true, "usuario" => $usuario]);
} else {
    echo json_encode(["success" => false, "error" => "Credenciales incorrectas"]);
}
?>
