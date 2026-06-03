<?php
header("Content-Type: application/json");
include '../conexion.php';

$id_libro         = intval(trim($_POST['id_libro']         ?? 0));
$id_usuario       = intval(trim($_POST['id_usuario']       ?? 0));
$fecha_prestamo   = trim($_POST['fecha_prestamo']           ?? '');
$fecha_devolucion = trim($_POST['fecha_devolucion']         ?? '');
$observaciones    = trim($_POST['observaciones']            ?? '');

// Validacion basica
if ($id_libro <= 0 || $id_usuario <= 0 || empty($fecha_prestamo)) {
    echo json_encode(["status" => "error", "mensaje" => "Libro, usuario y fecha son obligatorios"]);
    exit();
}

// Requisito 9: validar stock > 0 en el servidor
$res = $con->query("SELECT titulo, stock FROM libros WHERE id = $id_libro");
if ($res->num_rows === 0) {
    echo json_encode(["status" => "error", "mensaje" => "Libro no encontrado"]);
    exit();
}
$libro = $res->fetch_assoc();
if ($libro['stock'] <= 0) {
    echo json_encode(["status" => "error", "mensaje" => "El libro '{$libro['titulo']}' no tiene stock disponible"]);
    exit();
}

// Logica de negocio: registrar prestamo + stock -1 en transaccion atomica
$con->begin_transaction();
try {
    // Insertar prestamo con estado 'Activo' (default)
    if (empty($fecha_devolucion)) {
        $stmt = $con->prepare(
            "INSERT INTO prestamos (id_libro, id_usuario, fecha_prestamo, observaciones)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("iiss", $id_libro, $id_usuario, $fecha_prestamo, $observaciones);
    } else {
        $stmt = $con->prepare(
            "INSERT INTO prestamos (id_libro, id_usuario, fecha_prestamo, fecha_devolucion, observaciones)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("iisss", $id_libro, $id_usuario, $fecha_prestamo, $fecha_devolucion, $observaciones);
    }
    $stmt->execute();

    // Requisito 9: decrementar stock del libro
    $con->query("UPDATE libros SET stock = stock - 1 WHERE id = $id_libro");

    $con->commit();
    echo json_encode(["status" => "ok", "mensaje" => "Prestamo registrado correctamente"]);

} catch (Exception $e) {
    $con->rollback();
    echo json_encode(["status" => "error", "mensaje" => "Error al registrar el prestamo"]);
}
