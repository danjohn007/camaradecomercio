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
                <a href="<?php echo BASE_URL; ?>asistentes/export?<?php echo http_build_query($_GET); ?>" 
                   class="btn btn-outline-success">
                    <i class="fas fa-download"></i> Exportar
                </a>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted">Total Registros</div>
                            <div class="h4 mb-0"><?php echo number_format($stats['total_registros']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted">Asistieron</div>
                            <div class="h4 mb-0"><?php echo number_format($stats['total_asistieron']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted">Pendientes</div>
                            <div class="h4 mb-0"><?php echo number_format($stats['total_pendientes']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted">Cancelados</div>
                            <div class="h4 mb-0"><?php echo number_format($stats['total_cancelados']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de búsqueda -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2"></i>
                Filtros de Búsqueda
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo BASE_URL; ?>asistentes" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Búsqueda general</label>
                    <input type="text" class="form-control" name="search" 
                           value="<?php echo htmlspecialchars($search); ?>" 
                           placeholder="Nombre, email, empresa, código QR...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Evento</label>
                    <select class="form-select" name="evento">
                        <option value="">Todos los eventos</option>
                        <?php foreach ($eventos as $evt): ?>
                            <option value="<?php echo $evt['id']; ?>" 
                                    <?php echo $evento == $evt['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($evt['titulo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select class="form-select" name="estado">
                        <option value="">Todos los estados</option>
                        <option value="registrado" <?php echo $estado === 'registrado' ? 'selected' : ''; ?>>Registrado</option>
                        <option value="confirmado" <?php echo $estado === 'confirmado' ? 'selected' : ''; ?>>Confirmado</option>
                        <option value="asistio" <?php echo $estado === 'asistio' ? 'selected' : ''; ?>>Asistió</option>
                        <option value="no_asistio" <?php echo $estado === 'no_asistio' ? 'selected' : ''; ?>>No asistió</option>
                        <option value="cancelado" <?php echo $estado === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo</label>
                    <select class="form-select" name="tipo">
                        <option value="">Todos los tipos</option>
                        <option value="empresa" <?php echo $tipo === 'empresa' ? 'selected' : ''; ?>>Empresa</option>
                        <option value="invitado" <?php echo $tipo === 'invitado' ? 'selected' : ''; ?>>Invitado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-canaco">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="<?php echo BASE_URL; ?>asistentes" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de asistentes -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                Asistentes 
                <span class="badge bg-secondary"><?php echo number_format($total); ?></span>
            </h5>
            <?php if ($total > 0): ?>
                <small class="text-muted">
                    Mostrando <?php echo (($page - 1) * $perPage) + 1; ?> - 
                    <?php echo min($page * $perPage, $total); ?> de <?php echo number_format($total); ?> registros
                </small>
            <?php endif; ?>
        </div>
        <div class="card-body p-0">
            <?php if (empty($asistentes)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-users text-muted" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-muted">No se encontraron asistentes</h4>
                    <p class="text-muted">
                        <?php if (!empty($search) || !empty($evento) || !empty($estado) || !empty($tipo)): ?>
                            Intenta ajustar los filtros de búsqueda.
                        <?php else: ?>
                            Aún no hay asistentes registrados en el sistema.
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Código QR</th>
                                <th>Asistente</th>
                                <th>Evento</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Registro</th>
                                <th>Asistencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($asistentes as $asistente): ?>
                                <tr>
                                    <td>
                                        <code class="text-canaco"><?php echo htmlspecialchars($asistente['codigo_unico']); ?></code>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?php echo htmlspecialchars($asistente['nombre_registrante']); ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-envelope me-1"></i>
                                                <?php echo htmlspecialchars($asistente['email_registrante']); ?>
                                            </small>
                                            <?php if ($asistente['telefono_registrante']): ?>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone me-1"></i>
                                                    <?php echo htmlspecialchars($asistente['telefono_registrante']); ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?php echo htmlspecialchars($asistente['evento_titulo']); ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <?php echo date('d/m/Y H:i', strtotime($asistente['fecha_evento'])); ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($asistente['tipo_registrante'] === 'empresa'): ?>
                                            <span class="badge bg-primary">
                                                <i class="fas fa-building me-1"></i>
                                                Empresa
                                            </span>
                                            <?php if ($asistente['nombre_comercial']): ?>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars($asistente['nombre_comercial']); ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-info">
                                                <i class="fas fa-user me-1"></i>
                                                Invitado
                                            </span>
                                            <?php if ($asistente['ocupacion']): ?>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars($asistente['ocupacion']); ?></small>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'registrado' => 'bg-secondary',
                                            'confirmado' => 'bg-info',
                                            'asistio' => 'bg-success',
                                            'no_asistio' => 'bg-warning',
                                            'cancelado' => 'bg-danger'
                                        ];
                                        $statusIcon = [
                                            'registrado' => 'fas fa-clock',
                                            'confirmado' => 'fas fa-check',
                                            'asistio' => 'fas fa-check-circle',
                                            'no_asistio' => 'fas fa-times',
                                            'cancelado' => 'fas fa-ban'
                                        ];
                                        ?>
                                        <span class="badge <?php echo $statusClass[$asistente['estado']] ?? 'bg-secondary'; ?>">
                                            <i class="<?php echo $statusIcon[$asistente['estado']] ?? 'fas fa-question'; ?> me-1"></i>
                                            <?php echo ucfirst($asistente['estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('d/m/Y H:i', strtotime($asistente['created_at'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if ($asistente['fecha_asistencia']): ?>
                                            <small class="text-success">
                                                <?php echo date('d/m/Y H:i', strtotime($asistente['fecha_asistencia'])); ?>
                                            </small>
                                        <?php else: ?>
                                            <small class="text-muted">-</small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <?php if ($totalPages > 1): ?>
                    <div class="card-footer">
                        <nav aria-label="Paginación de asistentes">
                            <ul class="pagination justify-content-center mb-0">
                                <!-- Primera página -->
                                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>asistentes?<?php echo http_build_query(array_merge($_GET, ['page' => 1])); ?>">
                                        <i class="fas fa-angle-double-left"></i>
                                    </a>
                                </li>
                                
                                <!-- Página anterior -->
                                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>asistentes?<?php echo http_build_query(array_merge($_GET, ['page' => max(1, $page - 1)])); ?>">
                                        <i class="fas fa-angle-left"></i>
                                    </a>
                                </li>

                                <!-- Páginas numéricas -->
                                <?php
                                $start = max(1, $page - 2);
                                $end = min($totalPages, $page + 2);
                                
                                for ($i = $start; $i <= $end; $i++):
                                ?>
                                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                        <a class="page-link" href="<?php echo BASE_URL; ?>asistentes?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Página siguiente -->
                                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>asistentes?<?php echo http_build_query(array_merge($_GET, ['page' => min($totalPages, $page + 1)])); ?>">
                                        <i class="fas fa-angle-right"></i>
                                    </a>
                                </li>
                                
                                <!-- Última página -->
                                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>asistentes?<?php echo http_build_query(array_merge($_GET, ['page' => $totalPages])); ?>">
                                        <i class="fas fa-angle-double-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

<?php include 'app/views/layouts/footer.php'; ?>