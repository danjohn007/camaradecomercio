<?php $pageTitle = 'Iniciar Sesión'; ?>

<div class="row justify-content-center align-items-center" style="min-height: 100vh; background: linear-gradient(135deg, #4a7c59 0%, #2d5016 100%);">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-lg border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-building text-canaco" style="font-size: 3rem;"></i>
                    <h2 class="mt-3 text-canaco">CANACO</h2>
                    <p class="text-muted">Sistema de Eventos</p>
                </div>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo BASE_URL; ?>login">
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>
                            Correo Electrónico
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               placeholder="usuario@canaco.org.mx">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i>
                            Contraseña
                        </label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">
                            Recordarme
                        </label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-canaco btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Iniciar Sesión
                        </button>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="<?php echo BASE_URL; ?>forgot-password" class="text-muted text-decoration-none">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="text-center mt-4 text-white">
            <p class="mb-0">
                <strong>Usuarios de prueba:</strong><br>
                <small>
                    <strong>SuperAdmin:</strong> admin@canaco.org.mx / password<br>
                    <strong>Gestor:</strong> gestor@canaco.org.mx / password
                </small>
            </p>
        </div>
    </div>
</div>