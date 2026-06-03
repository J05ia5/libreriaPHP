<?php
header("Content-Type: application/json");
include '../conexion.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(["status" => "error", "mensaje" => "ID invalido"]);
    exit();
}

$stmt = $con->prepare("DELETE FROM usuarios WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "ok", "mensaje" => "Usuario eliminado correctamente"]);
} else {
    echo json_encode(["status" => "error", "mensaje" => "Error al eliminar el usuario"]);
}
