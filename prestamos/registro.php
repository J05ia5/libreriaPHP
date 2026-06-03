<?php
include '../conexion.php';
$libros   = $con->query("SELECT id, titulo, stock FROM libros WHERE stock > 0 ORDER BY titulo");
$usuarios = $con->query("SELECT id, nombre, carnet FROM usuarios ORDER BY nombre");
$sinStock = ($libros->num_rows === 0);
?>

<div class="card p-4" style="max-width: 600px; border: 1px solid var(--fluent-border-color);">
    <h4 class="mb-4">Nuevo Prestamo</h4>

    <?php if ($sinStock): ?>
        <div class="alert alert-warning">
            No hay libros con stock disponible para prestar.
            <a href="javascript:cargarContenido('libros/lista.php')" class="alert-link">Ver catalogo de libros</a>
        </div>
    <?php else: ?>

    <form id="form-prestamo" class="needs-validation" novalidate
          action="javascript:crearRegistro('form-prestamo', 'prestamos/create.php', 'prestamos/lista.php')">

        <!-- Requisito 8: select libro cargado dinamicamente desde BD -->
        <div class="mb-3">
            <label class="form-label">Libro <span class="text-danger">*</span></label>
            <select name="id_libro" class="form-select" required>
                <option value="">-- Seleccione un libro --</option>
                <?php while ($l = $libros->fetch_assoc()): ?>
                <option value="<?= $l['id'] ?>">
                    <?= htmlspecialchars($l['titulo']) ?> &nbsp;(Stock disponible: <?= $l['stock'] ?>)
                </option>
                <?php endwhile; ?>
            </select>
            <div class="invalid-feedback">Seleccione un libro.</div>
        </div>

        <!-- Requisito 8: select usuario cargado dinamicamente desde BD -->
        <div class="mb-3">
            <label class="form-label">Usuario <span class="text-danger">*</span></label>
            <select name="id_usuario" class="form-select" required>
                <option value="">-- Seleccione un usuario --</option>
                <?php while ($u = $usuarios->fetch_assoc()): ?>
                <option value="<?= $u['id'] ?>">
                    <?= htmlspecialchars($u['nombre']) ?> &nbsp;(<?= htmlspecialchars($u['carnet']) ?>)
                </option>
                <?php endwhile; ?>
            </select>
            <div class="invalid-feedback">Seleccione un usuario.</div>
        </div>

        <!-- Requisito 8: fecha de prestamo y fecha de devolucion -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha de prestamo <span class="text-danger">*</span></label>
                <input type="date" name="fecha_prestamo" class="form-control"
                       value="<?= date('Y-m-d') ?>" required>
                <div class="invalid-feedback">Ingrese la fecha de prestamo.</div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha de devolucion esperada</label>
                <input type="date" name="fecha_devolucion" class="form-control"
                       min="<?= date('Y-m-d') ?>">
            </div>
        </div>

        <!-- Requisito 8: observaciones opcionales -->
        <div class="mb-4">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" class="form-control" rows="3"
                      placeholder="Observaciones opcionales..."></textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm">Registrar Prestamo</button>
            <button type="button" class="btn btn-secondary btn-sm"
                    onclick="cargarContenido('prestamos/lista.php')">Cancelar</button>
        </div>
    </form>
    <?php endif; ?>
</div>
