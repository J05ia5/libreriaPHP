<?php
include '../conexion.php';
$resultado = $con->query("SELECT * FROM usuarios ORDER BY id DESC");
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Lista de Usuarios</h4>
    <button class="btn btn-primary btn-sm" onclick="cargarContenido('usuarios/registro.php')">+ Nuevo Usuario</button>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Carnet</th>
            <th>Telefono</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($resultado->num_rows === 0): ?>
        <tr><td colspan="6" class="text-center text-muted py-4">No hay usuarios registrados</td></tr>
    <?php else: ?>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $fila['id'] ?></td>
            <td><?= htmlspecialchars($fila['nombre']) ?></td>
            <td><code><?= htmlspecialchars($fila['carnet']) ?></code></td>
            <td><?= htmlspecialchars($fila['telefono']) ?></td>
            <td><?= htmlspecialchars($fila['correo']) ?></td>
            <td>
                <button class="btn btn-warning btn-sm" onclick="abrirEditarUsuario(
                    <?= $fila['id'] ?>,
                    <?= htmlspecialchars(json_encode($fila['nombre'])) ?>,
                    <?= htmlspecialchars(json_encode($fila['carnet'])) ?>,
                    <?= htmlspecialchars(json_encode($fila['telefono'] ?? '')) ?>,
                    <?= htmlspecialchars(json_encode($fila['correo'] ?? '')) ?>
                )">Editar</button>
                <button class="btn btn-danger btn-sm" onclick="confirmarEliminar(
                    'usuarios/delete.php?id=<?= $fila['id'] ?>',
                    <?= htmlspecialchars(json_encode($fila['nombre'])) ?>
                )">Eliminar</button>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php endif; ?>
    </tbody>
</table>
