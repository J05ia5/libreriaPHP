var urlEliminar = '';
var modalConfirmar, modalEditarLibro, modalEditarUsuario;

document.addEventListener('DOMContentLoaded', function() {
    modalConfirmar     = new bootstrap.Modal(document.getElementById('modalConfirmar'));
    modalEditarLibro   = new bootstrap.Modal(document.getElementById('modalEditarLibro'));
    modalEditarUsuario = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));

    // Confirmar eliminacion (libros, usuarios y prestamos Devuelto/Vencido)
    document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
        modalConfirmar.hide();
        fetch(urlEliminar)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            mostrarMensaje(data.status === 'ok' ? 'success' : 'danger', data.mensaje);
            if (data.status === 'ok') {
                var modulo = urlEliminar.split('/')[0];
                cargarContenido(modulo + '/lista.php');
            }
        });
    });

    // Guardar edicion libro
    document.getElementById('btnGuardarLibro').addEventListener('click', function() {
        var form = document.getElementById('formEditarLibro');
        form.classList.add('was-validated');
        if (!form.checkValidity()) return;
        fetch('libros/update.php', { method: 'POST', body: new FormData(form) })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            mostrarMensaje(data.status === 'ok' ? 'success' : 'danger', data.mensaje);
            if (data.status === 'ok') {
                modalEditarLibro.hide();
                cargarContenido('libros/lista.php');
            }
        });
    });

    // Guardar edicion usuario
    document.getElementById('btnGuardarUsuario').addEventListener('click', function() {
        var form = document.getElementById('formEditarUsuario');
        form.classList.add('was-validated');
        if (!form.checkValidity()) return;
        fetch('usuarios/update.php', { method: 'POST', body: new FormData(form) })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            mostrarMensaje(data.status === 'ok' ? 'success' : 'danger', data.mensaje);
            if (data.status === 'ok') {
                modalEditarUsuario.hide();
                cargarContenido('usuarios/lista.php');
            }
        });
    });
});

// Navegacion via XHR 
function cargarContenido(abrir) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', abrir, true);
    xhr.onload = function() {
        document.getElementById('contenido').innerHTML = this.responseText;
    };
    xhr.send();
}

// Crear nuevo registro via Fetch — espera JSON
function crearRegistro(idForm, url, urlLista) {
    var form = document.getElementById(idForm);
    form.classList.add('was-validated');
    if (!form.checkValidity()) return;
    fetch(url, { method: 'POST', body: new FormData(form) })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        mostrarMensaje(data.status === 'ok' ? 'success' : 'danger', data.mensaje);
        if (data.status === 'ok') {
            form.reset();
            form.classList.remove('was-validated');
            setTimeout(function() { cargarContenido(urlLista); }, 1200);
        }
    });
}

// Modal confirmacion eliminar
function confirmarEliminar(url, nombre) {
    urlEliminar = url;
    document.getElementById('modalNombreItem').textContent = nombre;
    modalConfirmar.show();
}

// Abrir modal editar libro con datos actuales
function abrirEditarLibro(id, titulo, autor, isbn, categoria, stock) {
    var form = document.getElementById('formEditarLibro');
    form.classList.remove('was-validated');
    document.getElementById('editLibroId').value        = id;
    document.getElementById('editLibroTitulo').value    = titulo;
    document.getElementById('editLibroAutor').value     = autor;
    document.getElementById('editLibroIsbn').value      = isbn;
    document.getElementById('editLibroCategoria').value = categoria;
    document.getElementById('editLibroStock').value     = stock;
    modalEditarLibro.show();
}

// Abrir modal editar usuario con datos actuales
function abrirEditarUsuario(id, nombre, carnet, telefono, correo) {
    var form = document.getElementById('formEditarUsuario');
    form.classList.remove('was-validated');
    document.getElementById('editUsuarioId').value       = id;
    document.getElementById('editUsuarioNombre').value   = nombre;
    document.getElementById('editUsuarioCarnet').value   = carnet;
    document.getElementById('editUsuarioTelefono').value = telefono;
    document.getElementById('editUsuarioCorreo').value   = correo;
    modalEditarUsuario.show();
}

// Cambiar estado de prestamo (Activo -> Devuelto / Vencido) via Fetch
function cambiarEstado(id, estado) {
    var accion = estado === 'Devuelto' ? 'marcar como Devuelto' : 'marcar como Vencido';
    if (!confirm('Desea ' + accion + ' este prestamo?')) return;
    var fd = new FormData();
    fd.append('id', id);
    fd.append('estado', estado);
    fetch('prestamos/update_estado.php', { method: 'POST', body: fd })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        mostrarMensaje(data.status === 'ok' ? 'success' : 'danger', data.mensaje);
        if (data.status === 'ok') cargarContenido('prestamos/lista.php');
    });
}

// Filtrar prestamos por libro, usuario o estado
function filtrarPrestamos() {
    var libro   = document.querySelector('#formFiltro [name="libro"]').value;
    var usuario = document.querySelector('#formFiltro [name="usuario"]').value;
    var estado  = document.querySelector('#formFiltro [name="estado"]').value;
    cargarContenido('prestamos/lista.php?libro='   + encodeURIComponent(libro) +
                                       '&usuario=' + encodeURIComponent(usuario) +
                                       '&estado='  + encodeURIComponent(estado));
}

// Alerta flotante global
function mostrarMensaje(tipo, texto) {
    var el = document.getElementById('mensajeGlobal');
    el.className = 'alert alert-' + tipo + ' alert-dismissible fade show';
    el.innerHTML = texto + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    el.style.display = 'block';
    setTimeout(function() {
        try { bootstrap.Alert.getOrCreateInstance(el).close(); } catch(e) { el.style.display = 'none'; }
    }, 4000);
}
