<?php
header("Content-Type: application/json");
include '../conexion.php';

$id        = intval($_POST['id'] ?? 0);
$titulo    = trim($_POST['titulo'] ?? '');
$autor     = trim($_POST['autor'] ?? '');
$isbn      = trim($_POST['isbn'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$stock     = intval($_POST['stock'] ?? 0);

if ($id <= 0 || empty($titulo) || empty($autor)) {
    echo json_encode(["status" => "error", "mensaje" => "Datos invalidos"]);
    exit();
}
if ($stock < 0) {
    echo json_encode(["status" => "error", "mensaje" => "El stock no puede ser negativo"]);
    exit();
}

$stmt = $con->prepare("UPDATE libros SET titulo=?, autor=?, isbn=?, categoria=?, stock=? WHERE id=?");
$stmt->bind_param("ssssii", $titulo, $autor, $isbn, $categoria, $stock, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "ok", "mensaje" => "Libro actualizado correctamente"]);
} else {
    echo json_encode(["status" => "error", "mensaje" => "Error al actualizar el libro"]);
}
