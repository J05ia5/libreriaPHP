<?php
require_once __DIR__ . '/conexion.php';

// Inicializar contadores
$totalLibros = 0;
$totalUsuarios = 0;
$sinStock = 0;
$totalEjemplares = 0;
$dbError = null;

try {
    // 1. Total Libros (Títulos diferentes)
    $totalLibros = $con->query("SELECT COUNT(*) FROM libros")->fetch_row()[0];

    // 2. Total Usuarios
    $totalUsuarios = $con->query("SELECT COUNT(*) FROM usuarios")->fetch_row()[0];

    // 3. Libros agotados
    $sinStock = $con->query("SELECT COUNT(*) FROM libros WHERE stock <= 0")->fetch_row()[0];

    // 4. Total de copias/ejemplares
    $totalEjemplares = $con->query("SELECT SUM(stock) FROM libros")->fetch_row()[0] ?: 0;
} catch (Exception $e) {
    $dbError = "Error al conectar o consultar la base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Sistema de Gestión de Biblioteca</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Fluent CSS -->
    <link href="css/estilos.css" rel="stylesheet">
    <style>
        /* Hero Section style following Microsoft UI guidelines */
        .fluent-hero {
            background-color: var(--ms-white);
            border: 1px solid var(--ms-gray-200);
            border-radius: 4px;
            padding: 48px 36px;
            margin-bottom: 40px;
            position: relative;
            background-image: linear-gradient(135deg, rgba(0, 120, 212, 0.05) 0%, rgba(255, 255, 255, 0.95) 100%);
            display: flex;
            align-items: center;
        }

        .fluent-hero-content {
            max-width: 700px;
        }
    </style>
</head>

<body>

    <!-- Sticky Navigation Bar (Microsoft Style) -->
    <nav class="navbar navbar-expand-lg navbar-fluent sticky-top">
        <div class="container-fluid" style="max-width: 1600px;">
            <a class="navbar-brand" href="index.php">
                <div class="fluent-logo-icon">
                    <span class="logo-blue"></span>
                    <span class="logo-blue"></span>
                    <span class="logo-blue"></span>
                    <span class="logo-blue"></span>
                </div>
                <span>Biblioteca Microsoft</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-3">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:cargarContenido('libros/registro.php')">Registrar Libro</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:cargarContenido('libros/lista.php')">Listado de Libros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:cargarContenido('usuarios/registro.php')">Registrar Usuario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:cargarContenido('usuarios/lista.php')">Listado de Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:cargarContenido('prestamos/registro.php')">Registrar Préstamo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:cargarContenido('prestamos/lista.php')">Listado de Préstamos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="main-content" id="contenido">

        <?php if ($dbError): ?>
            <div class="alert alert-warning mb-4" role="alert" style="border-radius: 2px;">
                <strong>Aviso de Base de Datos:</strong> <?php echo htmlspecialchars($dbError); ?><br>
                <small class="text-muted">Asegúrese de haber importado el archivo <code>bd_biblioteca.sql</code> para ver el funcionamiento correcto de las estadísticas.</small>
            </div>
        <?php endif; ?>

        <!-- Microsoft Fluent Hero Banner -->
        <section class="fluent-hero">
            <div class="fluent-hero-content">
                <span class="badge mb-2" style="background-color: var(--ms-blue); border-radius: 2px;">SIS 256 • Tecnología Web</span>
                <h1 class="display-6 mb-3" style="font-weight: 600;">Sistema de Gestión de Biblioteca</h1>
                <p class="lead text-muted mb-4">Administre eficazmente el catálogo de libros y el registro de usuarios de manera digital, rápida y totalmente asíncrona mediante la interfaz Fluent Design.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="javascript:cargarContenido('libros/registro.php')" class="btn-fluent-primary">Registrar Libro</a>
                    <a href="javascript:cargarContenido('usuarios/registro.php')" class="btn-fluent-secondary" style="color: var(--ms-black) !important;">Registrar Usuario</a>
                </div>
            </div>
        </section>

        <!-- Dynamic Statistics Cards Grid -->
        <h2 class="h4 mb-4" style="font-weight: 600;">Panel de Monitoreo</h2>
        <div class="fluent-grid">
            <!-- Card 1: Total Libros -->
            <div class="fluent-card">
                <div class="metric-accent-line metric-blue"></div>
                <div class="metric-value"><?php echo $totalLibros; ?></div>
                <div class="metric-label">Libros Registrados</div>
                <div class="text-muted mt-2" style="font-size: 13px; padding-left: 8px;">Títulos diferentes en catálogo</div>
            </div>

            <!-- Card 2: Total Copias -->
            <div class="fluent-card">
                <div class="metric-accent-line metric-yellow"></div>
                <div class="metric-value"><?php echo $totalEjemplares; ?></div>
                <div class="metric-label">Ejemplares en Stock</div>
                <div class="text-muted mt-2" style="font-size: 13px; padding-left: 8px;">Suma total del stock disponible</div>
            </div>

            <!-- Card 3: Total Usuarios -->
            <div class="fluent-card">
                <div class="metric-accent-line metric-green"></div>
                <div class="metric-value"><?php echo $totalUsuarios; ?></div>
                <div class="metric-label">Usuarios Activos</div>
                <div class="text-muted mt-2" style="font-size: 13px; padding-left: 8px;">Estudiantes y docentes habilitados</div>
            </div>

            <!-- Card 4: Libros Agotados -->
            <div class="fluent-card">
                <div class="metric-accent-line metric-red"></div>
                <div class="metric-value"><?php echo $sinStock; ?></div>
                <div class="metric-label">Libros sin Stock</div>
                <div class="text-muted mt-2" style="font-size: 13px; padding-left: 8px;">Ejemplares con stock igual a 0</div>
            </div>
        </div>

        <!-- Quick Access Section -->
        <div class="row g-4 mt-2">
            <div class="col-12 col-md-4">
                <div class="fluent-card h-100">
                    <h3 class="h5 mb-3" style="font-weight: 600;">Administración de Libros</h3>
                    <p class="text-muted">Gestione los títulos literarios de la biblioteca. Registre nuevos ejemplares e inspeccione las cantidades actuales en almacén.</p>
                    <button onclick="cargarContenido('libros/lista.php')" class="btn-fluent-secondary mt-auto align-self-start">Ir a Libros &rarr;</button>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="fluent-card h-100">
                    <h3 class="h5 mb-3" style="font-weight: 600;">Gestión de Lectores</h3>
                    <p class="text-muted">Administre el padrón de usuarios autorizados. Modifique información de contacto o dé de alta a nuevos alumnos y profesores.</p>
                    <button onclick="cargarContenido('usuarios/lista.php')" class="btn-fluent-secondary mt-auto align-self-start">Ir a Usuarios &rarr;</button>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="fluent-card h-100">
                    <h3 class="h5 mb-3" style="font-weight: 600;">Control de Préstamos</h3>
                    <p class="text-muted">Gestione el préstamo de libros a los usuarios, controle fechas de devolución y verifique préstamos vencidos.</p>
                    <button onclick="cargarContenido('prestamos/lista.php')" class="btn-fluent-secondary mt-auto align-self-start">Ir a Préstamos &rarr;</button>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer>
        <div class="container-fluid">
            <p class="m-0">&copy; 2026 Sistema de Gestión de Biblioteca. Inspirado en Microsoft Fluent Design.</p>
        </div>
    </footer>

    <!-- Alerta flotante global -->
    <div id="mensajeGlobal" class="alert alert-dismissible fade position-fixed"
        style="top: 20px; right: 20px; z-index: 9999; min-width: 320px; display: none;"></div>

    <!-- Modal: Confirmar eliminacion -->
    <div class="modal fade" id="modalConfirmar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <p class="mb-1">¿Está seguro de eliminar <strong id="modalNombreItem"></strong>?</p>
                    <p class="text-muted small mb-0">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer border-0">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary btn-sm btn-danger text-white" id="btnConfirmarEliminar">Sí, eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Libro -->
    <div class="modal fade" id="modalEditarLibro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Libro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarLibro" class="needs-validation" novalidate>
                        <input type="hidden" id="editLibroId" name="id">
                        <div class="mb-3">
                            <label class="form-label">Título <span class="text-danger">*</span></label>
                            <input type="text" id="editLibroTitulo" name="titulo" class="form-control" required>
                            <div class="invalid-feedback">El título es obligatorio.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Autor <span class="text-danger">*</span></label>
                            <input type="text" id="editLibroAutor" name="autor" class="form-control" required>
                            <div class="invalid-feedback">El autor es obligatorio.</div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">ISBN</label>
                                <input type="text" id="editLibroIsbn" name="isbn" class="form-control">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" id="editLibroStock" name="stock" class="form-control" min="0" required>
                                <div class="invalid-feedback">Stock inválido (mínimo 0).</div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Categoría</label>
                            <input type="text" id="editLibroCategoria" name="categoria" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary btn-sm" id="btnGuardarLibro">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Usuario -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarUsuario" class="needs-validation" novalidate>
                        <input type="hidden" id="editUsuarioId" name="id">
                        <div class="mb-3">
                            <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                            <input type="text" id="editUsuarioNombre" name="nombre" class="form-control" required>
                            <div class="invalid-feedback">El nombre es obligatorio.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Carnet <span class="text-danger">*</span></label>
                            <input type="text" id="editUsuarioCarnet" name="carnet" class="form-control" required>
                            <div class="invalid-feedback">El carnet es obligatorio.</div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" id="editUsuarioTelefono" name="telefono" class="form-control">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Correo</label>
                                <input type="email" id="editUsuarioCorreo" name="correo" class="form-control">
                                <div class="invalid-feedback">Formato de correo inválido.</div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary btn-sm" id="btnGuardarUsuario">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app.js"></script>
</body>

</html>