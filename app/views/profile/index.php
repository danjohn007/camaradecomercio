<?php 
$pageTitle = 'Mi Perfil'; 
$hideSidebar = true; // Hide main sidebar for profile pages
include 'app/views/layouts/header.php'; 
?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-user me-2"></i>Mi Perfil</h1>
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
                    <h5 class="mb-0">Información Personal</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong></p>
                            <p><?php echo htmlspecialchars($usuario['nombre']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email:</strong></p>
                            <p><?php echo htmlspecialchars($usuario['email']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Teléfono:</strong></p>
                            <p><?php echo htmlspecialchars($usuario['telefono'] ?? 'No especificado'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Rol:</strong></p>
                            <p>
                                <span class="badge bg-primary">
                                    <?php echo ucfirst($usuario['rol']); ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Estado:</strong></p>
                            <p>
                                <span class="badge <?php echo $usuario['activo'] ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $usuario['activo'] ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Registro:</strong></p>
                            <p><?php echo date('d/m/Y H:i', strtotime($usuario['created_at'])); ?></p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-3">
                        <a href="<?php echo BASE_URL; ?>perfil/editar" class="btn btn-canaco">
                            <i class="fas fa-edit me-2"></i>
                            Editar Perfil
                        </a>
                        <a href="<?php echo BASE_URL; ?>configuracion" class="btn btn-outline-secondary">
                            <i class="fas fa-cog me-2"></i>
                            Configuración
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Accesos Rápidos</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>configuracion/password" class="btn btn-outline-warning">
                            <i class="fas fa-key me-2"></i>
                            Cambiar Contraseña
                        </a>
                        <a href="<?php echo BASE_URL; ?>dashboard" class="btn btn-outline-primary">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                        <a href="<?php echo BASE_URL; ?>eventos" class="btn btn-outline-success">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Mis Eventos
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Estadísticas</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-1">Última actualización:</p>
                    <p><?php echo $usuario['updated_at'] ? date('d/m/Y H:i', strtotime($usuario['updated_at'])) : 'Nunca'; ?></p>
                </div>
            </div>
        </div>
    </div>

<?php include 'app/views/layouts/footer.php'; ?>