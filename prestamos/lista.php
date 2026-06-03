<?php
include '../conexion.php';

// Requisito 12: leer filtros desde GET
$filtroLibro   = trim($_GET['libro']   ?? '');
$filtroUsuario = trim($_GET['usuario'] ?? '');
$filtroEstado  = trim($_GET['estado']  ?? '');

// Construir WHERE dinamico
$where  = [];
$params = [];
$types  = '';

if ($filtroLibro !== '') {
    $where[]  = "l.titulo LIKE ?";
    $params[] = "%$filtroLibro%";
    $types   .= 's';
}
if ($filtroUsuario !== '') {
    $where[]  = "u.nombre LIKE ?";
    $params[] = "%$filtroUsuario%";
    $types   .= 's';
}
if (in_array($filtroEstado, ['Activo', 'Devuelto', 'Vencido'])) {
    $where[]  = "p.estado = ?";
    $params[] = $filtroEstado;
    $types   .= 's';
}

// Requisito 10: JOIN con libros y usuarios
$sql_query = "SELECT p.id,
                     l.titulo,
                     u.nombre   AS usuario,
                     p.fecha_prestamo,
                     p.fecha_devolucion,
                     p.estado,
                     p.observaciones
              FROM   prestamos p
              JOIN   libros    l ON p.id_libro   = l.id
              JOIN   usuarios  u ON p.id_usuario = u.id";

if (!empty($where)) {
    $sql_query .= " WHERE " . implode(' AND ', $where);
}
$sql_query .= " ORDER BY p.id DESC";

if (!empty($params)) {
    $stmt = $con->prepare($sql_query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $resultado = $con->query($sql_query);
}

$hoy = date('Y-m-d');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Lista de Prestamos</h4>
    <button class="btn btn-primary btn-sm"
            onclick="cargarContenido('prestamos/registro.php')">+ Nuevo Prestamo</button>
</div>

<!-- Requisito 12: filtros por libro, usuario y estado -->
<form id="formFiltro" class="row g-2 mb-3" onsubmit="return false;">
    <div class="col-md-4">
        <input type="text" name="libro" class="form-control form-control-sm"
               placeholder="Buscar por libro..." value="<?= htmlspecialchars($filtroLibro) ?>">
    </div>
    <div class="col-md-3">
        <input type="text" name="usuario" class="form-control form-control-sm"
               placeholder="Buscar por usuario..." value="<?= htmlspecialchars($filtroUsuario) ?>">
    </div>
    <div class="col-md-3">
        <select name="estado" class="form-select form-select-sm">
            <option value="">Todos los estados</option>
            <option value="Activo"   <?= $filtroEstado === 'Activo'   ? 'selected' : '' ?>>Activo</option>
            <option value="Devuelto" <?= $filtroEstado === 'Devuelto' ? 'selected' : '' ?>>Devuelto</option>
            <option value="Vencido"  <?= $filtroEstado === 'Vencido'  ? 'selected' : '' ?>>Vencido</option>
        </select>
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-secondary btn-sm w-100"
                onclick="filtrarPrestamos()">Filtrar</button>
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-outline-secondary btn-sm w-100"
                onclick="cargarContenido('prestamos/lista.php')">X</button>
    </div>
</form>

<table class="table table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Libro</th>
            <th>Usuario</th>
            <th>F. Prestamo</th>
            <th>F. Devolucion</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($resultado->num_rows === 0): ?>
        <tr>
            <td colspan="7" class="text-center text-muted py-4">No hay prestamos registrados</td>
        </tr>
    <?php else: ?>
        <?php while ($fila = $resultado->fetch_assoc()):
            // Requisito 13: vencido = estado Activo y fecha_devolucion pasada
            $esVencido = (
                $fila['estado'] === 'Activo' &&
                !empty($fila['fecha_devolucion']) &&
                $fila['fecha_devolucion'] < $hoy
            );
        ?>
        <!-- Requisito 13: fila en rojo si esta vencido -->
        <tr class="<?= $esVencido ? 'table-danger' : '' ?>">
            <td><?= $fila['id'] ?></td>
            <td><?= htmlspecialchars($fila['titulo']) ?></td>
            <td><?= htmlspecialchars($fila['usuario']) ?></td>
            <td><?= $fila['fecha_prestamo'] ?></td>
            <td>
                <?= $fila['fecha_devolucion'] ?: '<span class="text-muted">—</span>' ?>
                <?php if ($esVencido): ?>
                    <span class="badge bg-danger ms-1">VENCIDO</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($fila['estado'] === 'Activo'): ?>
                    <span class="badge bg-primary">Activo</span>
                <?php elseif ($fila['estado'] === 'Devuelto'): ?>
                    <span class="badge bg-success">Devuelto</span>
                <?php else: ?>
                    <span class="badge bg-secondary">Vencido</span>
                <?php endif; ?>
            </td>
            <td>
                <!-- Requisito 11: cambiar estado via Fetch sin recargar pagina -->
                <?php if ($fila['estado'] === 'Activo'): ?>
                    <button class="btn btn-success btn-sm"
                        onclick="cambiarEstado(<?= $fila['id'] ?>, 'Devuelto')">
                        Devuelto
                    </button>
                    <button class="btn btn-warning btn-sm"
                        onclick="cambiarEstado(<?= $fila['id'] ?>, 'Vencido')">
                        Vencido
                    </button>
                <?php endif; ?>
                <!-- Logica de negocio: eliminar solo si NO es Activo -->
                <?php if ($fila['estado'] !== 'Activo'): ?>
                    <button class="btn btn-danger btn-sm"
                        onclick="confirmarEliminar(
                            'prestamos/delete.php?id=<?= $fila['id'] ?>',
                            <?= htmlspecialchars(json_encode('prestamo #' . $fila['id'] . ' de ' . $fila['titulo'])) ?>
                        )">
                        Eliminar
                    </button>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php endif; ?>
    </tbody>
</table>
