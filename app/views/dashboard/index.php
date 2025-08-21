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

<!-- Gráficas del dashboard -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Eventos por Estado
                </h5>
            </div>
            <div class="card-body">
                <canvas id="eventosEstadoChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Asistentes por Evento
                </h5>
            </div>
            <div class="card-body">
                <canvas id="asistentesEventoChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Cupo vs Ocupados
                </h5>
            </div>
            <div class="card-body">
                <canvas id="cupoOcupadosChart" width="400" height="300"></canvas>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfica de Eventos por Estado
    const eventosEstadoCtx = document.getElementById('eventosEstadoChart').getContext('2d');
    new Chart(eventosEstadoCtx, {
        type: 'pie',
        data: {
            labels: ['Publicados', 'Borradores', 'Cerrados', 'Cancelados'],
            datasets: [{
                data: [12, 5, 8, 2],
                backgroundColor: [
                    '#28a745',
                    '#ffc107', 
                    '#6c757d',
                    '#dc3545'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfica de Asistentes por Evento
    const asistentesEventoCtx = document.getElementById('asistentesEventoChart').getContext('2d');
    new Chart(asistentesEventoCtx, {
        type: 'bar',
        data: {
            labels: ['Foro Innovación', 'Conferencia Tech', 'Expo Negocios', 'Seminario Digital', 'Workshop AI'],
            datasets: [{
                label: 'Asistentes',
                data: [85, 120, 98, 76, 134],
                backgroundColor: '#007bff',
                borderColor: '#0056b3',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Gráfica de Cupo vs Ocupados
    const cupoOcupadosCtx = document.getElementById('cupoOcupadosChart').getContext('2d');
    new Chart(cupoOcupadosCtx, {
        type: 'line',
        data: {
            labels: ['Evento 1', 'Evento 2', 'Evento 3', 'Evento 4', 'Evento 5'],
            datasets: [{
                label: 'Cupo Máximo',
                data: [150, 200, 120, 100, 180],
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                fill: false,
                tension: 0.4
            }, {
                label: 'Ocupados',
                data: [85, 120, 98, 76, 134],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)', 
                fill: false,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>