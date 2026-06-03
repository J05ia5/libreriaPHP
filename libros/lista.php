<?php
include '../conexion.php';
$resultado = $con->query("SELECT * FROM libros ORDER BY id DESC");
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Lista de Libros</h4>
    <button class="btn btn-primary btn-sm" onclick="cargarContenido('libros/registro.php')">+ Nuevo Libro</button>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Titulo</th>
            <th>Autor</th>
            <th>ISBN</th>
            <th>Categoria</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($resultado->num_rows === 0): ?>
        <tr><td colspan="7" class="text-center text-muted py-4">No hay libros registrados</td></tr>
    <?php else: ?>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $fila['id'] ?></td>
            <td><?= htmlspecialchars($fila['titulo']) ?></td>
            <td><?= htmlspecialchars($fila['autor']) ?></td>
            <td><code><?= htmlspecialchars($fila['isbn']) ?></code></td>
            <td><?= htmlspecialchars($fila['categoria']) ?></td>
            <td>
                <?php if ($fila['stock'] == 0): ?>
                    <span class="badge bg-danger"><?= $fila['stock'] ?></span>
                <?php elseif ($fila['stock'] <= 2): ?>
                    <span class="badge bg-warning text-dark"><?= $fila['stock'] ?></span>
                <?php else: ?>
                    <span class="badge bg-success"><?= $fila['stock'] ?></span>
                <?php endif; ?>
            </td>
            <td>
                <button class="btn btn-warning btn-sm" onclick="abrirEditarLibro(
                    <?= $fila['id'] ?>,
                    <?= htmlspecialchars(json_encode($fila['titulo'])) ?>,
                    <?= htmlspecialchars(json_encode($fila['autor'])) ?>,
                    <?= htmlspecialchars(json_encode($fila['isbn'] ?? '')) ?>,
                    <?= htmlspecialchars(json_encode($fila['categoria'] ?? '')) ?>,
                    <?= $fila['stock'] ?>
                )">Editar</button>
                <button class="btn btn-danger btn-sm" onclick="confirmarEliminar(
                    'libros/delete.php?id=<?= $fila['id'] ?>',
                    <?= htmlspecialchars(json_encode($fila['titulo'])) ?>
                )">Eliminar</button>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php endif; ?>
    </tbody>
</table>
