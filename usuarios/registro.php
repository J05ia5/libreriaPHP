<div class="card p-4" style="max-width: 550px; border: 1px solid var(--fluent-border-color);">
    <h4 class="mb-4">Nuevo Usuario</h4>
    <form id="form-usuario" class="needs-validation" novalidate
          action="javascript:crearRegistro('form-usuario', 'usuarios/create.php', 'usuarios/lista.php')">

        <div class="mb-3">
            <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
            <input type="text" name="nombre" class="form-control"
                   placeholder="Ej: Juan Perez" required>
            <div class="invalid-feedback">El nombre es obligatorio.</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Carnet <span class="text-danger">*</span></label>
            <input type="text" name="carnet" class="form-control"
                   placeholder="Ej: 20210001" required>
            <div class="invalid-feedback">El carnet es obligatorio.</div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label class="form-label">Telefono</label>
                <input type="text" name="telefono" class="form-control" placeholder="Ej: 70012345">
            </div>
            <div class="col-md-6 mb-4">
                <label class="form-label">Correo</label>
                <input type="email" name="correo" class="form-control"
                       placeholder="correo@ejemplo.com">
                <div class="invalid-feedback">Formato de correo invalido.</div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            <button type="button" class="btn btn-secondary btn-sm"
                    onclick="cargarContenido('usuarios/lista.php')">Cancelar</button>
        </div>
    </form>
</div>
