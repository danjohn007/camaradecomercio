<?php $pageTitle = 'Dashboard'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt me-2 text-canaco"></i>
        Dashboard
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download"></i> Exportar
            </button>
        </div>
    </div>
</div>

<!-- Métricas principales -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card card-metric h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Total Eventos</h6>
                        <h2 class="mb-0"><?php echo number_format($metrics['totalEventos']); ?></h2>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card card-metric h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Eventos Hoy</h6>
                        <h2 class="mb-0"><?php echo number_format($metrics['eventosHoy']); ?></h2>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card card-metric h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Total Asistentes</h6>
                        <h2 class="mb-0"><?php echo number_format($metrics['totalAsistentes']); ?></h2>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card card-metric h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Ocupación Promedio</h6>
                        <h2 class="mb-0"><?php echo $metrics['ocupacionPromedio']; ?>%</h2>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Eventos próximos -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-check me-2"></i>
                    Próximos Eventos
                </h5>
                <a href="<?php echo BASE_URL; ?>eventos" class="btn btn-sm btn-outline-primary">
                    Ver todos
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($eventosProximos)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">No hay eventos próximos</p>
                        <a href="<?php echo BASE_URL; ?>eventos/crear" class="btn btn-canaco">
                            <i class="fas fa-plus me-2"></i>Crear Evento
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Evento</th>
                                    <th>Fecha</th>
                                    <th>Asistentes</th>
                                    <th>Ocupación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($eventosProximos as $evento): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($evento['titulo']); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo htmlspecialchars($evento['ubicacion']); ?></small>
                                    </td>
                                    <td>
                                        <small><?php echo date('d/m/Y H:i', strtotime($evento['fecha_evento'])); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo $evento['asistentes_registrados']; ?> / <?php echo $evento['cupo_maximo']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $ocupacion = $evento['cupo_maximo'] > 0 ? round(($evento['asistentes_registrados'] / $evento['cupo_maximo']) * 100, 1) : 0;
                                        $badgeClass = $ocupacion >= 80 ? 'bg-danger' : ($ocupacion >= 60 ? 'bg-warning' : 'bg-success');
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>"><?php echo $ocupacion; ?>%</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo BASE_URL; ?>eventos/editar/<?php echo $evento['id']; ?>" 
                                               class="btn btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>evento/<?php echo $evento['slug']; ?>" 
                                               class="btn btn-outline-success" title="Ver página pública" target="_blank">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
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
    </div>
    
    <!-- Actividad reciente -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Actividad Reciente
                </h5>
            </div>
            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                <?php if (empty($actividadReciente)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-clock text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2 mb-0">No hay actividad reciente</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($actividadReciente as $actividad): ?>
                        <div class="list-group-item border-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo htmlspecialchars($actividad['accion']); ?></h6>
                                <small class="text-muted"><?php echo date('H:i', strtotime($actividad['created_at'])); ?></small>
                            </div>
                            <p class="mb-1 small"><?php echo htmlspecialchars($actividad['descripcion']); ?></p>
                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>
                                <?php echo htmlspecialchars($actividad['usuario_nombre']); ?>
                            </small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>