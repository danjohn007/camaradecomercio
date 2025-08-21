<?php 
$pageTitle = 'Configuración'; 
$hideSidebar = true; // Hide main sidebar for profile pages
include 'app/views/layouts/header.php'; 
?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-cog me-2"></i>Configuración</h1>
        <a href="<?php echo BASE_URL; ?>perfil" class="btn btn-outline-secondary">
            <i class="fas fa-user me-2"></i>
            Ver Perfil
        </a>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Preferencias del Sistema</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>configuracion">
                        <div class="row g-3">
                            <div class="col-12">
                                <h6 class="text-canaco">Notificaciones</h6>
                                <hr>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notificaciones_email" 
                                           name="notificaciones_email" <?php echo $configuraciones['notificaciones_email'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="notificaciones_email">
                                        <strong>Notificaciones por Email</strong><br>
                                        <small class="text-muted">Recibir notificaciones del sistema por correo electrónico</small>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notificaciones_evento" 
                                           name="notificaciones_evento" <?php echo $configuraciones['notificaciones_evento'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="notificaciones_evento">
                                        <strong>Recordatorios de Eventos</strong><br>
                                        <small class="text-muted">Recibir recordatorios antes de los eventos</small>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <h6 class="text-canaco">Apariencia</h6>
                                <hr>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="tema_oscuro" 
                                           name="tema_oscuro" <?php echo $configuraciones['tema_oscuro'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="tema_oscuro">
                                        <strong>Tema Oscuro</strong><br>
                                        <small class="text-muted">Usar tema oscuro para la interfaz (próximamente)</small>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="idioma" class="form-label">Idioma</label>
                                <select class="form-select" id="idioma" name="idioma">
                                    <option value="es" <?php echo $configuraciones['idioma'] === 'es' ? 'selected' : ''; ?>>Español</option>
                                    <option value="en" <?php echo $configuraciones['idioma'] === 'en' ? 'selected' : ''; ?>>English (próximamente)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-canaco">
                                <i class="fas fa-save me-2"></i>
                                Guardar Configuración
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="location.reload()">
                                <i class="fas fa-undo me-2"></i>
                                Restablecer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Seguridad</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>configuracion/password" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>
                            Cambiar Contraseña
                        </a>
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-shield-alt me-2"></i>
                            <strong>Recomendación:</strong><br>
                            Cambia tu contraseña regularmente para mantener tu cuenta segura.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Información del Sistema</h5>
                </div>
                <div class="card-body">
                    <p><strong>Versión:</strong> <?php echo APP_VERSION; ?></p>
                    <p><strong>Última actualización:</strong> <?php echo date('d/m/Y'); ?></p>
                    <p><strong>Soporte:</strong> eventos@canaco.org.mx</p>
                </div>
            </div>
        </div>
    </div>

<?php include 'app/views/layouts/footer.php'; ?>