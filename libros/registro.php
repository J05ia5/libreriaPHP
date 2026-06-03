<div class="card p-4" style="max-width: 550px; border: 1px solid var(--fluent-border-color);">
    <h4 class="mb-4">Nuevo Libro</h4>
    <form id="form-libro" class="needs-validation" novalidate
          action="javascript:crearRegistro('form-libro', 'libros/create.php', 'libros/lista.php')">

        <div class="mb-3">
            <label class="form-label">Titulo <span class="text-danger">*</span></label>
            <input type="text" name="titulo" class="form-control"
                   placeholder="Ej: Cien anos de soledad" required>
            <div class="invalid-feedback">El titulo es obligatorio.</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Autor <span class="text-danger">*</span></label>
            <input type="text" name="autor" class="form-control"
                   placeholder="Ej: Gabriel Garcia Marquez" required>
            <div class="invalid-feedback">El autor es obligatorio.</div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">ISBN</label>
                <input type="text" name="isbn" class="form-control" placeholder="978-...">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Stock <span class="text-danger">*</span></label>
                <input type="number" name="stock" class="form-control" value="1" min="0" required>
                <div class="invalid-feedback">Stock invalido (minimo 0).</div>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Categoria</label>
            <input type="text" name="categoria" class="form-control"
                   placeholder="Ej: Novela, Ciencia...">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            <button type="button" class="btn btn-secondary btn-sm"
                    onclick="cargarContenido('libros/lista.php')">Cancelar</button>
        </div>
    </form>
</div>
