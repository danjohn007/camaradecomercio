<?php 
$pageTitle = 'Editar Perfil'; 
$hideSidebar = true; // Hide main sidebar for profile pages
include 'app/views/layouts/header.php'; 
?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-edit me-2"></i>Editar Perfil</h1>
        <a href="<?php echo BASE_URL; ?>perfil" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Volver al Perfil
        </a>
    </div>
    
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
                    <h5 class="mb-0">Información Personal</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>perfil/editar">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre completo *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Correo electrónico *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>" 
                                       placeholder="10 dígitos">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Rol</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo ucfirst($usuario['rol']); ?>" readonly>
                                <div class="form-text">El rol no puede ser modificado.</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Estado</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo $usuario['activo'] ? 'Activo' : 'Inactivo'; ?>" readonly>
                                <div class="form-text">El estado no puede ser modificado.</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Fecha de registro</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo date('d/m/Y H:i', strtotime($usuario['created_at'])); ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-canaco">
                                <i class="fas fa-save me-2"></i>
                                Guardar Cambios
                            </button>
                            <a href="<?php echo BASE_URL; ?>perfil" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Importante:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Todos los campos marcados con * son obligatorios.</li>
                            <li>El email debe ser único en el sistema.</li>
                            <li>Los cambios se aplicarán inmediatamente.</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Acciones Adicionales</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>configuracion/password" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-key me-2"></i>
                            Cambiar Contraseña
                        </a>
                        <a href="<?php echo BASE_URL; ?>configuracion" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-cog me-2"></i>
                            Configuración
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de teléfono
    const telefonoInput = document.getElementById('telefono');
    telefonoInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length > 10) {
            this.value = this.value.substring(0, 10);
        }
    });
    
    telefonoInput.addEventListener('blur', function() {
        if (this.value && this.value.length !== 10) {
            this.classList.add('is-invalid');
            let feedback = this.parentNode.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                this.parentNode.appendChild(feedback);
            }
            feedback.textContent = 'El teléfono debe tener exactamente 10 dígitos.';
        } else {
            this.classList.remove('is-invalid');
        }
    });
});
</script>

<?php include 'app/views/layouts/footer.php'; ?>