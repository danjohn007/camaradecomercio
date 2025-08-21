<?php $pageTitle = 'Gestión de Eventos'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-alt me-2 text-canaco"></i>
        Gestión de Eventos
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo BASE_URL; ?>eventos/crear" class="btn btn-canaco">
                <i class="fas fa-plus me-1"></i>
                Nuevo Evento
            </a>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Título o descripción...">
            </div>
            <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="borrador" <?php echo $estado === 'borrador' ? 'selected' : ''; ?>>Borrador</option>
                    <option value="publicado" <?php echo $estado === 'publicado' ? 'selected' : ''; ?>>Publicado</option>
                    <option value="cerrado" <?php echo $estado === 'cerrado' ? 'selected' : ''; ?>>Cerrado</option>
                    <option value="cancelado" <?php echo $estado === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="fecha_inicio" class="form-label">Fecha desde</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                       value="<?php echo htmlspecialchars($fechaInicio); ?>">
            </div>
            <div class="col-md-2">
                <label for="fecha_fin" class="form-label">Fecha hasta</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                       value="<?php echo htmlspecialchars($fechaFin); ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-canaco me-2">
                    <i class="fas fa-search me-1"></i>
                    Filtrar
                </button>
                <a href="<?php echo BASE_URL; ?>eventos" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>
                    Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Lista de eventos -->
<?php if (empty($eventos)): ?>
    <div class="text-center py-5">
        <i class="fas fa-calendar-times text-muted" style="font-size: 5rem;"></i>
        <h3 class="mt-3 text-muted">No hay eventos</h3>
        <p class="text-muted">
            <?php if (!empty($search) || !empty($estado) || !empty($fechaInicio) || !empty($fechaFin)): ?>
                No se encontraron eventos con los filtros aplicados.
            <?php else: ?>
                Aún no has creado ningún evento.
            <?php endif; ?>
        </p>
        <a href="<?php echo BASE_URL; ?>eventos/crear" class="btn btn-canaco btn-lg">
            <i class="fas fa-plus me-2"></i>
            Crear Primer Evento
        </a>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($eventos as $evento): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card event-card h-100">
                    <?php if ($evento['imagen_banner']): ?>
                        <img src="<?php echo BASE_URL . UPLOAD_PATH; ?>eventos/<?php echo $evento['imagen_banner']; ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($evento['titulo']); ?>" 
                             style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="event-status">
                        <?php
                        $statusColors = [
                            'borrador' => 'secondary',
                            'publicado' => 'success',
                            'cerrado' => 'warning',
                            'cancelado' => 'danger'
                        ];
                        $statusColor = $statusColors[$evento['estado']] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?php echo $statusColor; ?>">
                            <?php echo ucfirst($evento['estado']); ?>
                        </span>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($evento['titulo']); ?></h5>
                        <p class="card-text text-muted small mb-2">
                            <i class="fas fa-calendar me-1"></i>
                            <?php echo date('d/m/Y H:i', strtotime($evento['fecha_evento'])); ?>
                        </p>
                        <p class="card-text text-muted small mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            <?php echo htmlspecialchars(substr($evento['ubicacion'], 0, 50)) . (strlen($evento['ubicacion']) > 50 ? '...' : ''); ?>
                        </p>
                        <p class="card-text text-muted small mb-3">
                            <i class="fas fa-users me-1"></i>
                            <?php echo $evento['total_asistentes']; ?> / <?php echo $evento['cupo_maximo']; ?> asistentes
                            <?php if ($evento['costo'] > 0): ?>
                                | <i class="fas fa-dollar-sign me-1"></i>
                                $<?php echo number_format($evento['costo'], 2); ?>
                            <?php else: ?>
                                | <span class="text-success">Gratuito</span>
                            <?php endif; ?>
                        </p>
                        
                        <p class="card-text flex-grow-1">
                            <?php echo htmlspecialchars(substr($evento['descripcion'], 0, 100)) . (strlen($evento['descripcion']) > 100 ? '...' : ''); ?>
                        </p>
                        
                        <div class="mt-auto">
                            <div class="btn-group w-100" role="group">
                                <a href="<?php echo BASE_URL; ?>eventos/editar/<?php echo $evento['id']; ?>" 
                                   class="btn btn-outline-primary btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <?php if ($evento['estado'] === 'publicado'): ?>
                                    <a href="<?php echo BASE_URL; ?>evento/<?php echo $evento['slug']; ?>" 
                                       class="btn btn-outline-success btn-sm" title="Ver página pública" target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <a href="<?php echo BASE_URL; ?>asistentes?evento=<?php echo $evento['id']; ?>" 
                                   class="btn btn-outline-info btn-sm" title="Ver asistentes">
                                    <i class="fas fa-users"></i>
                                </a>
                                
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                            data-bs-toggle="dropdown" title="Más opciones">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-copy me-2"></i>Clonar
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-download me-2"></i>Exportar CSV
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        
                                        <!-- Cambiar estado -->
                                        <?php if ($evento['estado'] !== 'publicado'): ?>
                                            <li>
                                                <a class="dropdown-item text-success" href="#" 
                                                   onclick="CANACO.events.toggleStatus(<?php echo $evento['id']; ?>, 'publicado')">
                                                    <i class="fas fa-play me-2"></i>Publicar
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        
                                        <?php if ($evento['estado'] === 'publicado'): ?>
                                            <li>
                                                <a class="dropdown-item text-warning" href="#" 
                                                   onclick="CANACO.events.toggleStatus(<?php echo $evento['id']; ?>, 'cerrado')">
                                                    <i class="fas fa-pause me-2"></i>Cerrar
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        
                                        <?php if ($evento['estado'] !== 'cancelado'): ?>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" 
                                                   onclick="CANACO.events.toggleStatus(<?php echo $evento['id']; ?>, 'cancelado')">
                                                    <i class="fas fa-ban me-2"></i>Cancelar
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" 
                                               onclick="CANACO.events.delete(<?php echo $evento['id']; ?>, '<?php echo addslashes($evento['titulo']); ?>')">
                                                <i class="fas fa-trash me-2"></i>Eliminar
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer text-muted small">
                        <i class="fas fa-user me-1"></i>
                        Creado por <?php echo htmlspecialchars($evento['usuario_nombre']); ?>
                        <span class="float-end">
                            <?php echo date('d/m/Y', strtotime($evento['created_at'])); ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Información adicional -->
    <div class="mt-4 text-muted text-center">
        <p>
            <strong><?php echo count($eventos); ?></strong> evento(s) encontrado(s)
            <?php if (!empty($search) || !empty($estado) || !empty($fechaInicio) || !empty($fechaFin)): ?>
                con los filtros aplicados
            <?php endif; ?>
        </p>
    </div>
<?php endif; ?>