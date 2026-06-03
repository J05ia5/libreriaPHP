<?php
header("Content-Type: application/json");
include '../conexion.php';

$id       = intval($_POST['id'] ?? 0);
$nombre   = trim($_POST['nombre'] ?? '');
$carnet   = trim($_POST['carnet'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo   = trim($_POST['correo'] ?? '');

if ($id <= 0 || empty($nombre) || empty($carnet)) {
    echo json_encode(["status" => "error", "mensaje" => "Datos invalidos"]);
    exit();
}

$stmt = $con->prepare("UPDATE usuarios SET nombre=?, carnet=?, telefono=?, correo=? WHERE id=?");
$stmt->bind_param("ssssi", $nombre, $carnet, $telefono, $correo, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "ok", "mensaje" => "Usuario actualizado correctamente"]);
} else {
    echo json_encode(["status" => "error", "mensaje" => "Error al actualizar el usuario"]);
}
