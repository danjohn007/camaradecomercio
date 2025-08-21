<?php 
$pageTitle = 'Cambiar Contraseña'; 
include 'app/views/layouts/header.php'; 
?>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-key me-2"></i>Cambiar Contraseña</h1>
        <a href="<?php echo BASE_URL; ?>configuracion" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Volver a Configuración
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
                    <h5 class="mb-0">Cambiar Contraseña</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>configuracion/password" id="passwordForm">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="password_actual" class="form-label">Contraseña actual *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_actual" name="password_actual" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_actual')">
                                        <i class="fas fa-eye" id="toggleIcon_password_actual"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password_nuevo" class="form-label">Nueva contraseña *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_nuevo" name="password_nuevo" 
                                           required minlength="6">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_nuevo')">
                                        <i class="fas fa-eye" id="toggleIcon_password_nuevo"></i>
                                    </button>
                                </div>
                                <div class="form-text">Mínimo 6 caracteres</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password_confirm" class="form-label">Confirmar nueva contraseña *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" 
                                           required minlength="6">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirm')">
                                        <i class="fas fa-eye" id="toggleIcon_password_confirm"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div id="passwordStrength" class="mt-2"></div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>
                                Cambiar Contraseña
                            </button>
                            <a href="<?php echo BASE_URL; ?>configuracion" class="btn btn-outline-secondary">
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
                    <h5 class="mb-0">Consejos de Seguridad</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>Recomendaciones:</strong>
                        <ul class="mt-2 mb-0">
                            <li>Usa al menos 8 caracteres</li>
                            <li>Incluye mayúsculas y minúsculas</li>
                            <li>Añade números y símbolos</li>
                            <li>No uses información personal</li>
                            <li>No reutilices contraseñas anteriores</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">¿Olvidaste tu contraseña?</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted">
                        Si no recuerdas tu contraseña actual, contacta al administrador del sistema 
                        para que pueda restablecerla.
                    </p>
                    <p class="small"><strong>Email de soporte:</strong> eventos@canaco.org.mx</p>
                </div>
            </div>
        </div>
    </div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById('toggleIcon_' + fieldId);
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.getElementById('password_nuevo');
    const confirmField = document.getElementById('password_confirm');
    const strengthDiv = document.getElementById('passwordStrength');
    const form = document.getElementById('passwordForm');
    
    // Verificar fortaleza de la contraseña
    passwordField.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        let strengthText = '';
        let strengthClass = '';
        
        if (password.length >= 6) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        switch (strength) {
            case 0:
            case 1:
                strengthText = 'Muy débil';
                strengthClass = 'text-danger';
                break;
            case 2:
                strengthText = 'Débil';
                strengthClass = 'text-warning';
                break;
            case 3:
                strengthText = 'Regular';
                strengthClass = 'text-info';
                break;
            case 4:
                strengthText = 'Fuerte';
                strengthClass = 'text-success';
                break;
            case 5:
                strengthText = 'Muy fuerte';
                strengthClass = 'text-success fw-bold';
                break;
        }
        
        if (password.length > 0) {
            strengthDiv.innerHTML = `<small class="${strengthClass}">Fortaleza: ${strengthText}</small>`;
        } else {
            strengthDiv.innerHTML = '';
        }
    });
    
    // Validar que las contraseñas coincidan
    confirmField.addEventListener('input', function() {
        if (this.value !== passwordField.value) {
            this.classList.add('is-invalid');
            let feedback = this.parentNode.parentNode.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                this.parentNode.parentNode.appendChild(feedback);
            }
            feedback.textContent = 'Las contraseñas no coinciden';
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    // Validar formulario antes de enviar
    form.addEventListener('submit', function(e) {
        if (passwordField.value !== confirmField.value) {
            e.preventDefault();
            confirmField.classList.add('is-invalid');
            alert('Las contraseñas no coinciden');
        }
    });
});
</script>

<?php include 'app/views/layouts/footer.php'; ?>