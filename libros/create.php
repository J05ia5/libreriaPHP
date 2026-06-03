<?php
header("Content-Type: application/json");
include '../conexion.php';

$titulo    = trim($_POST['titulo'] ?? '');
$autor     = trim($_POST['autor'] ?? '');
$isbn      = trim($_POST['isbn'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$stock     = intval($_POST['stock'] ?? 1);

if (empty($titulo) || empty($autor)) {
    echo json_encode(["status" => "error", "mensaje" => "Titulo y autor son obligatorios"]);
    exit();
}
if ($stock < 0) {
    echo json_encode(["status" => "error", "mensaje" => "El stock no puede ser negativo"]);
    exit();
}

$stmt = $con->prepare("INSERT INTO libros (titulo, autor, isbn, categoria, stock) VALUES (?,?,?,?,?)");
$stmt->bind_param("ssssi", $titulo, $autor, $isbn, $categoria, $stock);

if ($stmt->execute()) {
    echo json_encode(["status" => "ok", "mensaje" => "Libro registrado correctamente"]);
} else {
    echo json_encode(["status" => "error", "mensaje" => "Error al registrar el libro"]);
}
