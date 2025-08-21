<?php 
$pageTitle = 'Asistentes'; 
include 'app/views/layouts/header.php'; 
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
<?php 
$pageTitle = 'Asistentes'; 
include 'app/views/layouts/header.php'; 
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-users me-2 text-canaco"></i>
        Gestión de Asistentes
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo BASE_URL; ?>asistentes/export" class="btn btn-outline-success">
                <i class="fas fa-download"></i> Exportar CSV
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm card-metric">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="metric-icon me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-0">Total Registrados</h6>
                        <h3 class="mb-0 text-canaco"><?php echo number_format($stats['total']); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm card-metric">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="metric-icon me-3">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-0">Asistieron</h6>
                        <h3 class="mb-0 text-success"><?php echo number_format($stats['asistieron']); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm card-metric">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="metric-icon me-3">
                        <i class="fas fa-clock text-primary"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-0">Registrados</h6>
                        <h3 class="mb-0 text-primary"><?php echo number_format($stats['registrados']); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm card-metric">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="metric-icon me-3">
                        <i class="fas fa-times-circle text-danger"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-0">Cancelados</h6>
                        <h3 class="mb-0 text-danger"><?php echo number_format($stats['cancelados']); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>asistentes" class="row g-3">
            <div class="col-md-3">
                <label for="evento" class="form-label">Evento</label>
                <select class="form-select" id="evento" name="evento">
                    <option value="">Todos los eventos</option>
                    <?php foreach ($eventos as $evento): ?>
                        <option value="<?php echo $evento['id']; ?>" <?php echo $filtros['evento'] == $evento['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($evento['titulo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="registrado" <?php echo $filtros['estado'] === 'registrado' ? 'selected' : ''; ?>>Registrado</option>
                    <option value="confirmado" <?php echo $filtros['estado'] === 'confirmado' ? 'selected' : ''; ?>>Confirmado</option>
                    <option value="asistio" <?php echo $filtros['estado'] === 'asistio' ? 'selected' : ''; ?>>Asistió</option>
                    <option value="no_asistio" <?php echo $filtros['estado'] === 'no_asistio' ? 'selected' : ''; ?>>No Asistió</option>
                    <option value="cancelado" <?php echo $filtros['estado'] === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-select" id="tipo" name="tipo">
                    <option value="">Todos</option>
                    <option value="empresa" <?php echo $filtros['tipo'] === 'empresa' ? 'selected' : ''; ?>>Empresa</option>
                    <option value="invitado" <?php echo $filtros['tipo'] === 'invitado' ? 'selected' : ''; ?>>Invitado</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="buscar" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="buscar" name="buscar" 
                       value="<?php echo htmlspecialchars($filtros['buscar']); ?>" 
                       placeholder="Nombre, email, código...">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-canaco">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Asistentes -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            Lista de Asistentes (<?php echo count($asistentes); ?> registros)
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($asistentes)): ?>
            <div class="text-center py-5">
                <i class="fas fa-users text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3 text-muted">No se encontraron asistentes</h5>
                <p class="text-muted">No hay registros que coincidan con los filtros seleccionados.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Asistente</th>
                            <th>Evento</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asistentes as $asistente): ?>
                            <tr>
                                <td>
                                    <code><?php echo htmlspecialchars($asistente['codigo_unico']); ?></code>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($asistente['nombre_asistente']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($asistente['email_asistente']); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <?php echo htmlspecialchars($asistente['evento_titulo']); ?><br>
                                        <small class="text-muted">
                                            <?php echo date('d/m/Y H:i', strtotime($asistente['fecha_evento'])); ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge <?php echo $asistente['tipo_registrante'] === 'empresa' ? 'bg-primary' : 'bg-info'; ?>">
                                        <?php echo $asistente['tipo_registrante'] === 'empresa' ? 'Empresa' : 'Invitado'; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $estadoClases = [
                                        'registrado' => 'bg-secondary',
                                        'confirmado' => 'bg-primary',
                                        'asistio' => 'bg-success',
                                        'no_asistio' => 'bg-warning',
                                        'cancelado' => 'bg-danger'
                                    ];
                                    $estadoTextos = [
                                        'registrado' => 'Registrado',
                                        'confirmado' => 'Confirmado',
                                        'asistio' => 'Asistió',
                                        'no_asistio' => 'No Asistió',
                                        'cancelado' => 'Cancelado'
                                    ];
                                    ?>
                                    <span class="badge <?php echo $estadoClases[$asistente['estado']] ?? 'bg-secondary'; ?>">
                                        <?php echo $estadoTextos[$asistente['estado']] ?? 'Desconocido'; ?>
                                    </span>
                                    <?php if ($asistente['fecha_asistencia']): ?>
                                        <br><small class="text-muted">
                                            <?php echo date('d/m/Y H:i', strtotime($asistente['fecha_asistencia'])); ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d/m/Y H:i', strtotime($asistente['created_at'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                onclick="showAttendeeDetail(<?php echo $asistente['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                    type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item" 
                                                            onclick="updateStatus(<?php echo $asistente['id']; ?>, 'asistio')">
                                                        <i class="fas fa-check text-success me-2"></i>Marcar como Asistió
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item" 
                                                            onclick="updateStatus(<?php echo $asistente['id']; ?>, 'no_asistio')">
                                                        <i class="fas fa-times text-warning me-2"></i>Marcar como No Asistió
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item" 
                                                            onclick="updateStatus(<?php echo $asistente['id']; ?>, 'cancelado')">
                                                        <i class="fas fa-ban text-danger me-2"></i>Cancelar
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para actualizar estado -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Actualizar Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas actualizar el estado de este asistente?</p>
                <form id="statusForm" method="POST" action="<?php echo BASE_URL; ?>asistentes/update-status">
                    <input type="hidden" id="attendeeId" name="attendee_id">
                    <input type="hidden" id="newStatus" name="status">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-canaco" onclick="confirmStatusUpdate()">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(attendeeId, status) {
    document.getElementById('attendeeId').value = attendeeId;
    document.getElementById('newStatus').value = status;
    
    const statusTexts = {
        'asistio': 'Asistió',
        'no_asistio': 'No Asistió',
        'cancelado': 'Cancelado'
    };
    
    document.querySelector('#statusModal .modal-body p').textContent = 
        `¿Estás seguro de que deseas marcar este asistente como "${statusTexts[status]}"?`;
    
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

function confirmStatusUpdate() {
    document.getElementById('statusForm').submit();
}

function showAttendeeDetail(attendeeId) {
    // Implementar modal de detalles si es necesario
    window.location.href = `<?php echo BASE_URL; ?>asistentes/detail/${attendeeId}`;
}
</script>

<?php include 'app/views/layouts/footer.php'; ?>