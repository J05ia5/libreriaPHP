<?php
header("Content-Type: application/json");
include '../conexion.php';

$nombre   = trim($_POST['nombre'] ?? '');
$carnet   = trim($_POST['carnet'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo   = trim($_POST['correo'] ?? '');

if (empty($nombre) || empty($carnet)) {
    echo json_encode(["status" => "error", "mensaje" => "Nombre y carnet son obligatorios"]);
    exit();
}

$stmt = $con->prepare("INSERT INTO usuarios (nombre, carnet, telefono, correo) VALUES (?,?,?,?)");
$stmt->bind_param("ssss", $nombre, $carnet, $telefono, $correo);

if ($stmt->execute()) {
    echo json_encode(["status" => "ok", "mensaje" => "Usuario registrado correctamente"]);
} else {
    echo json_encode(["status" => "error", "mensaje" => "Error: el carnet ya esta registrado"]);
}
