<?php $pageTitle = 'Usuarios'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-cog me-2 text-canaco"></i>
        Gestión de Usuarios
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo BASE_URL; ?>usuarios/crear" class="btn btn-canaco">
                <i class="fas fa-plus"></i> Nuevo Usuario
            </a>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" placeholder="Buscar por nombre o email" 
                       value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="rol">
                    <option value="">Todos los roles</option>
                    <option value="superadmin" <?php echo $rol === 'superadmin' ? 'selected' : ''; ?>>Super Admin</option>
                    <option value="gestor" <?php echo $rol === 'gestor' ? 'selected' : ''; ?>>Gestor</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="activo">
                    <option value="">Todos los estados</option>
                    <option value="1" <?php echo $activo === '1' ? 'selected' : ''; ?>>Activos</option>
                    <option value="0" <?php echo $activo === '0' ? 'selected' : ''; ?>>Inactivos</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                <a href="<?php echo BASE_URL; ?>usuarios" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Lista de usuarios -->
<?php if (empty($usuarios)): ?>
<div class="text-center py-5">
    <i class="fas fa-user-cog text-muted" style="font-size: 5rem;"></i>
    <h3 class="mt-3 text-muted">No hay usuarios</h3>
    <p class="text-muted">No se encontraron usuarios con los filtros aplicados.</p>
    <a href="<?php echo BASE_URL; ?>usuarios/crear" class="btn btn-canaco">
        <i class="fas fa-plus"></i> Crear primer usuario
    </a>
</div>
<?php else: ?>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Teléfono</th>
                    <th>Eventos</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-light rounded-circle me-3 d-flex align-items-center justify-content-center">
                                <i class="fas fa-user text-muted"></i>
                            </div>
                            <div>
                                <h6 class="mb-0"><?php echo htmlspecialchars($usuario['nombre']); ?></h6>
                                <small class="text-muted">ID: <?php echo $usuario['id']; ?></small>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                    <td>
                        <?php if ($usuario['rol'] === 'superadmin'): ?>
                            <span class="badge bg-danger">Super Admin</span>
                        <?php else: ?>
                            <span class="badge bg-info">Gestor</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($usuario['telefono'] ?: '-'); ?></td>
                    <td>
                        <span class="badge bg-secondary"><?php echo $usuario['total_eventos']; ?></span>
                        <small class="text-muted">(<?php echo $usuario['eventos_publicados']; ?> publicados)</small>
                    </td>
                    <td>
                        <?php if ($usuario['activo']): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <small><?php echo date('d/m/Y H:i', strtotime($usuario['created_at'])); ?></small>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="<?php echo BASE_URL; ?>usuarios/editar/<?php echo $usuario['id']; ?>" 
                               class="btn btn-outline-primary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if ($usuario['id'] != $_SESSION['user_id']): ?>
                            <button type="button" class="btn btn-outline-warning" 
                                    onclick="toggleUserStatus(<?php echo $usuario['id']; ?>, <?php echo $usuario['activo'] ? 'false' : 'true'; ?>)"
                                    title="<?php echo $usuario['activo'] ? 'Desactivar' : 'Activar'; ?>">
                                <i class="fas fa-power-off"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger" 
                                    onclick="deleteUser(<?php echo $usuario['id']; ?>, '<?php echo htmlspecialchars($usuario['nombre']); ?>')"
                                    title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<script>
function toggleUserStatus(userId, activate) {
    const action = activate ? 'activar' : 'desactivar';
    if (confirm(`¿Estás seguro de que quieres ${action} este usuario?`)) {
        fetch(`${CANACO.baseUrl}usuarios/cambiar-estado/${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                CANACO.utils.showAlert(`Usuario ${action} correctamente`, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                CANACO.utils.showAlert(data.error || 'Error al cambiar el estado', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            CANACO.utils.showAlert('Error de conexión', 'danger');
        });
    }
}

function deleteUser(userId, userName) {
    if (confirm(`¿Estás seguro de que quieres eliminar al usuario "${userName}"?\n\nEsta acción no se puede deshacer.`)) {
        window.location.href = `${CANACO.baseUrl}usuarios/eliminar/${userId}`;
    }
}
</script>