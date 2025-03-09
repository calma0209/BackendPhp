<?php
// Filename: ObtenerPerfil.php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    echo json_encode(["error" => "Método no permitido"]);
    exit();
}

if (!isset($_GET['id_usuario'])) {
    echo json_encode(["error" => "Falta el id_usuario"]);
    exit();
}

$id_usuario = trim($_GET['id_usuario']);

$api_url = "http://localhost:8080/api/usuarios/" . $id_usuario;
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 200) {
    echo $response; 
} elseif ($http_code === 404) {
    echo json_encode(["error" => "Usuario no encontrado"]);
} else {
    echo json_encode(["error" => "Error desconocido al obtener el usuario"]);
}
?>
