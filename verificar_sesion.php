<?php
session_start();

if (!isset($_SESSION["id_usuario"]) && isset($_COOKIE["user_session"])) {
    session_id($_COOKIE["user_session"]); 
    session_start();
}


if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}
?>