<?php $pageTitle = 'Nuevo Usuario'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-plus me-2 text-canaco"></i>
        Nuevo Usuario
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo BASE_URL; ?>usuarios" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Información del Usuario</h5>
            </div>
            <div class="card-body">
                <form method="POST" id="userForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required
                                       value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
                                <select class="form-select" id="rol" name="rol" required>
                                    <option value="">Seleccionar rol</option>
                                    <option value="gestor" <?php echo ($_POST['rol'] ?? '') === 'gestor' ? 'selected' : ''; ?>>
                                        Gestor de Eventos
                                    </option>
                                    <option value="superadmin" <?php echo ($_POST['rol'] ?? '') === 'superadmin' ? 'selected' : ''; ?>>
                                        Super Administrador
                                    </option>
                                </select>
                                <div class="form-text">
                                    <strong>Gestor:</strong> Puede crear y gestionar eventos.<br>
                                    <strong>Super Admin:</strong> Acceso completo al sistema.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono"
                                       value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Mínimo 6 caracteres</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="confirmar_password" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="confirmar_password" name="confirmar_password" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo BASE_URL; ?>usuarios" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-canaco">
                            <i class="fas fa-save"></i> Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Información de Roles</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6><i class="fas fa-user-shield text-danger me-2"></i>Super Administrador</h6>
                    <ul class="list-unstyled text-muted small">
                        <li><i class="fas fa-check text-success me-1"></i> Gestión completa de usuarios</li>
                        <li><i class="fas fa-check text-success me-1"></i> Todos los permisos de eventos</li>
                        <li><i class="fas fa-check text-success me-1"></i> Acceso a reportes avanzados</li>
                        <li><i class="fas fa-check text-success me-1"></i> Configuración del sistema</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6><i class="fas fa-user-tie text-info me-2"></i>Gestor de Eventos</h6>
                    <ul class="list-unstyled text-muted small">
                        <li><i class="fas fa-check text-success me-1"></i> Crear y editar eventos</li>
                        <li><i class="fas fa-check text-success me-1"></i> Gestionar asistentes</li>
                        <li><i class="fas fa-check text-success me-1"></i> Validación QR</li>
                        <li><i class="fas fa-check text-success me-1"></i> Reportes básicos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userForm');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmar_password');
    
    // Validar contraseñas coincidan
    function validatePasswords() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Las contraseñas no coinciden');
        } else {
            confirmPassword.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', validatePasswords);
    confirmPassword.addEventListener('input', validatePasswords);
    
    // Formatear teléfono
    const telefono = document.getElementById('telefono');
    telefono.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });
});
</script>