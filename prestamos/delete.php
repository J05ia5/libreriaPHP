<?php
header("Content-Type: application/json");
include '../conexion.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(["status" => "error", "mensaje" => "ID invalido"]);
    exit();
}

// Verificar que el prestamo exista
$res = $con->query("SELECT estado FROM prestamos WHERE id = $id");
if ($res->num_rows === 0) {
    echo json_encode(["status" => "error", "mensaje" => "Prestamo no encontrado"]);
    exit();
}

$prestamo = $res->fetch_assoc();

// Logica de negocio: no permitir eliminar si estado es Activo
if ($prestamo['estado'] === 'Activo') {
    echo json_encode([
        "status"  => "error",
        "mensaje" => "No se puede eliminar un prestamo Activo. Primero marquelo como Devuelto o Vencido."
    ]);
    exit();
}

// Solo se elimina si es Devuelto o Vencido
$stmt = $con->prepare("DELETE FROM prestamos WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "ok", "mensaje" => "Prestamo eliminado correctamente"]);
} else {
    echo json_encode(["status" => "error", "mensaje" => "Error al eliminar el prestamo"]);
}
