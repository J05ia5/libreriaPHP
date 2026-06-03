<?php
header("Content-Type: application/json");
include '../conexion.php';

$id     = intval($_POST['id']     ?? 0);
$estado = trim($_POST['estado']   ?? '');

// Solo se permite cambiar a Devuelto o Vencido
if ($id <= 0 || !in_array($estado, ['Devuelto', 'Vencido'])) {
    echo json_encode(["status" => "error", "mensaje" => "Datos invalidos"]);
    exit();
}

$con->begin_transaction();
try {
    // Verificar que el prestamo exista y este Activo
    $res = $con->query("SELECT id_libro, estado FROM prestamos WHERE id = $id");
    if ($res->num_rows === 0) throw new Exception("Prestamo no encontrado");

    $prestamo = $res->fetch_assoc();
    if ($prestamo['estado'] !== 'Activo') {
        throw new Exception("Solo se puede cambiar el estado de prestamos Activos");
    }

    // Cambiar estado del prestamo
    $stmt = $con->prepare("UPDATE prestamos SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $estado, $id);
    $stmt->execute();

    // Logica de negocio:
    // Devuelto  -> stock del libro +1
    // Vencido   -> solo cambia estado, stock NO se incrementa
    if ($estado === 'Devuelto') {
        $con->query("UPDATE libros SET stock = stock + 1 WHERE id = " . $prestamo['id_libro']);
        $mensaje = "Libro devuelto correctamente — stock actualizado";
    } else {
        $mensaje = "Prestamo marcado como Vencido";
    }

    $con->commit();
    echo json_encode(["status" => "ok", "mensaje" => $mensaje]);

} catch (Exception $e) {
    $con->rollback();
    echo json_encode(["status" => "error", "mensaje" => $e->getMessage()]);
}
