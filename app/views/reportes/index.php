<?php $pageTitle = 'Reportes'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-bar me-2 text-canaco"></i>
        Reportes y Estadísticas
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-download"></i> Exportar
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>reportes/export?type=general&format=csv">
                    <i class="fas fa-chart-line me-2"></i>Estadísticas Generales (CSV)
                </a></li>
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>reportes/export?type=eventos&format=csv">
                    <i class="fas fa-calendar me-2"></i>Reporte de Eventos (CSV)
                </a></li>
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>reportes/export?type=asistentes&format=csv">
                    <i class="fas fa-users me-2"></i>Reporte de Asistentes (CSV)
                </a></li>
                <?php if ($_SESSION['user_role'] === 'superadmin'): ?>
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>reportes/export?type=usuarios&format=csv">
                    <i class="fas fa-user-cog me-2"></i>Reporte de Usuarios (CSV)
                </a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<!-- Tarjetas de estadísticas -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card card-metric border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-alt metric-icon"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="card-title mb-0"><?php echo number_format($stats['total_eventos']); ?></h5>
                        <p class="card-text text-muted mb-0">Total Eventos</p>
                        <small class="text-success">
                            <i class="fas fa-eye me-1"></i><?php echo $stats['eventos_publicados']; ?> publicados
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card card-metric border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users metric-icon"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="card-title mb-0"><?php echo number_format($stats['total_registros']); ?></h5>
                        <p class="card-text text-muted mb-0">Total Registros</p>
                        <small class="text-info">
                            <i class="fas fa-check me-1"></i><?php echo $stats['asistentes_confirmados']; ?> asistieron
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card card-metric border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-percentage metric-icon"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="card-title mb-0"><?php echo $stats['tasa_asistencia']; ?>%</h5>
                        <p class="card-text text-muted mb-0">Tasa de Asistencia</p>
                        <small class="<?php echo $stats['tasa_asistencia'] >= 70 ? 'text-success' : ($stats['tasa_asistencia'] >= 50 ? 'text-warning' : 'text-danger'); ?>">
                            <i class="fas fa-chart-line me-1"></i>Promedio general
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card card-metric border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock metric-icon"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="card-title mb-0"><?php echo number_format($stats['eventos_proximos']); ?></h5>
                        <p class="card-text text-muted mb-0">Eventos Próximos</p>
                        <small class="text-primary">
                            <i class="fas fa-calendar-day me-1"></i>Próximos 30 días
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Gráfico de Estados de Asistencia -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2 text-canaco"></i>
                    Estados de Asistencia
                </h6>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" width="300" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Gráfico de Tendencia Mensual -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2 text-canaco"></i>
                    Tendencia Mensual (Últimos 12 meses)
                </h6>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" width="800" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top 10 Eventos -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-trophy me-2 text-canaco"></i>
                    Top 10 Eventos Recientes
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($eventStats)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No hay eventos para mostrar</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Evento</th>
                                    <th class="text-center">Registrados</th>
                                    <th class="text-center">Asistieron</th>
                                    <th class="text-center">Tasa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($eventStats as $evento): ?>
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0" style="font-size: 0.875rem;"><?php echo htmlspecialchars($evento['titulo']); ?></h6>
                                            <small class="text-muted"><?php echo date('d/m/Y', strtotime($evento['fecha_evento'])); ?></small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary"><?php echo $evento['total_registrados']; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?php echo $evento['total_asistieron']; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?php echo $evento['tasa_asistencia'] >= 70 ? 'bg-success' : ($evento['tasa_asistencia'] >= 50 ? 'bg-warning' : 'bg-danger'); ?>">
                                            <?php echo $evento['tasa_asistencia'] ?? 0; ?>%
                                        </span>
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
    
    <!-- Estadísticas de Usuarios -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-user-friends me-2 text-canaco"></i>
                    Actividad de Usuarios
                </h6>
            </div>
            <div class="card-body">
                <!-- Distribución de Roles -->
                <div class="mb-4">
                    <h6 class="text-muted mb-3">Distribución de Roles</h6>
                    <?php foreach ($userStats['roles'] as $rol): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><?php echo ucfirst($rol['rol']); ?></span>
                        <span class="badge <?php echo $rol['rol'] === 'superadmin' ? 'bg-danger' : 'bg-info'; ?>">
                            <?php echo $rol['total']; ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Top Usuarios -->
                <div>
                    <h6 class="text-muted mb-3">Top Gestores de Eventos</h6>
                    <?php if (empty($userStats['actividad'])): ?>
                        <p class="text-muted text-center">No hay actividad para mostrar</p>
                    <?php else: ?>
                        <?php foreach ($userStats['actividad'] as $usuario): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="fw-medium"><?php echo htmlspecialchars($usuario['nombre']); ?></span>
                                <small class="text-muted d-block">
                                    <?php echo $usuario['eventos_publicados']; ?> de <?php echo $usuario['eventos_creados']; ?> publicados
                                </small>
                            </div>
                            <span class="badge bg-canaco"><?php echo $usuario['eventos_creados']; ?></span>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Configuración común para gráficos
Chart.defaults.font.family = 'system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial';
Chart.defaults.color = '#6c757d';

// Gráfico de Estados de Asistencia
const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
const attendanceData = <?php echo json_encode($attendanceStats); ?>;

const attendanceColors = {
    'registrado': '#6c757d',
    'confirmado': '#0dcaf0',
    'asistio': '#198754',
    'no_asistio': '#dc3545'
};

const attendanceLabels = {
    'registrado': 'Registrado',
    'confirmado': 'Confirmado',
    'asistio': 'Asistió',
    'no_asistio': 'No Asistió'
};

new Chart(attendanceCtx, {
    type: 'doughnut',
    data: {
        labels: attendanceData.map(item => attendanceLabels[item.estado] || item.estado),
        datasets: [{
            data: attendanceData.map(item => item.total),
            backgroundColor: attendanceData.map(item => attendanceColors[item.estado] || '#6c757d'),
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            }
        }
    }
});

// Gráfico de Tendencia Mensual
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthlyData = <?php echo json_encode($monthlyStats); ?>;

const monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: monthlyData.map(item => {
            const [year, month] = item.mes.split('-');
            return monthNames[parseInt(month) - 1] + ' ' + year;
        }),
        datasets: [{
            label: 'Eventos',
            data: monthlyData.map(item => item.eventos),
            borderColor: '#4a7c59',
            backgroundColor: 'rgba(74, 124, 89, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Registros',
            data: monthlyData.map(item => item.registros),
            borderColor: '#0dcaf0',
            backgroundColor: 'rgba(13, 202, 240, 0.1)',
            tension: 0.4
        }, {
            label: 'Asistencias',
            data: monthlyData.map(item => item.asistencias),
            borderColor: '#198754',
            backgroundColor: 'rgba(25, 135, 84, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 20
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    drawBorder: false
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>

<style>
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}

.card-metric {
    transition: transform 0.2s;
}

.card-metric:hover {
    transform: translateY(-2px);
}

.bg-canaco {
    background-color: var(--canaco-green) !important;
}
</style>