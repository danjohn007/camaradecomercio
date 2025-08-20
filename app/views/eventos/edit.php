<?php $pageTitle = 'Editar Evento'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2 text-canaco"></i>
        Editar Evento
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <?php if ($evento['estado'] === 'publicado'): ?>
                <a href="<?php echo BASE_URL; ?>evento/<?php echo $evento['slug']; ?>" 
                   class="btn btn-outline-success" target="_blank">
                    <i class="fas fa-external-link-alt me-1"></i>
                    Ver Página Pública
                </a>
            <?php endif; ?>
        </div>
        <a href="<?php echo BASE_URL; ?>eventos" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Volver a Eventos
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <form method="POST" enctype="multipart/form-data" data-validate="true">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Información General
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="titulo" class="form-label">Título del Evento *</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required 
                                   value="<?php echo htmlspecialchars($evento['titulo']); ?>">
                        </div>
                        
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción *</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?php echo htmlspecialchars($evento['descripcion']); ?></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="fecha_evento" class="form-label">Fecha y Hora *</label>
                            <input type="datetime-local" class="form-control" id="fecha_evento" name="fecha_evento" required
                                   value="<?php echo date('Y-m-d\TH:i', strtotime($evento['fecha_evento'])); ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="cupo_maximo" class="form-label">Cupo Máximo *</label>
                            <input type="number" class="form-control" id="cupo_maximo" name="cupo_maximo" 
                                   min="1" max="10000" required value="<?php echo $evento['cupo_maximo']; ?>">
                        </div>
                        
                        <div class="col-12">
                            <label for="ubicacion" class="form-label">Ubicación *</label>
                            <textarea class="form-control" id="ubicacion" name="ubicacion" rows="2" required><?php echo htmlspecialchars($evento['ubicacion']); ?></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="costo" class="form-label">Costo del Evento</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="costo" name="costo" 
                                       min="0" step="0.01" value="<?php echo $evento['costo']; ?>">
                            </div>
                            <div class="form-text">Dejar en 0 para eventos gratuitos</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="tipo_publico" class="form-label">Tipo de Público *</label>
                            <select class="form-select" id="tipo_publico" name="tipo_publico" required>
                                <option value="todos" <?php echo $evento['tipo_publico'] === 'todos' ? 'selected' : ''; ?>>Todos (Empresas e Invitados)</option>
                                <option value="empresas" <?php echo $evento['tipo_publico'] === 'empresas' ? 'selected' : ''; ?>>Solo Empresas</option>
                                <option value="invitados" <?php echo $evento['tipo_publico'] === 'invitados' ? 'selected' : ''; ?>>Solo Invitados</option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label for="imagen_banner" class="form-label">Imagen/Banner del Evento</label>
                            <?php if ($evento['imagen_banner']): ?>
                                <div class="mb-2">
                                    <img src="<?php echo BASE_URL . UPLOAD_PATH; ?>eventos/<?php echo $evento['imagen_banner']; ?>" 
                                         class="img-thumbnail" alt="Banner actual" style="max-height: 150px;">
                                    <div class="form-text text-muted">Imagen actual</div>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="imagen_banner" name="imagen_banner" 
                                   accept="image/jpeg,image/png,image/gif">
                            <div class="form-text">
                                Formato: JPG, PNG o GIF. Tamaño máximo: 5MB. 
                                <?php if ($evento['imagen_banner']): ?>
                                    Subir nueva imagen reemplazará la actual.
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>
                        Configuración
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="estado" class="form-label">Estado del Evento</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="borrador" <?php echo $evento['estado'] === 'borrador' ? 'selected' : ''; ?>>Borrador (no visible al público)</option>
                                <option value="publicado" <?php echo $evento['estado'] === 'publicado' ? 'selected' : ''; ?>>Publicado (visible al público)</option>
                                <option value="cerrado" <?php echo $evento['estado'] === 'cerrado' ? 'selected' : ''; ?>>Cerrado (no se permiten más registros)</option>
                                <option value="cancelado" <?php echo $evento['estado'] === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">URL Pública</label>
                            <div class="input-group">
                                <span class="input-group-text"><?php echo BASE_URL; ?>evento/</span>
                                <input type="text" class="form-control" value="<?php echo $evento['slug']; ?>" readonly>
                            </div>
                            <div class="form-text">Esta URL se genera automáticamente desde el título</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="<?php echo BASE_URL; ?>eventos" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-canaco">
                    <i class="fas fa-save me-1"></i>
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Estadísticas del Evento
                </h5>
            </div>
            <div class="card-body">
                <?php
                // Las estadísticas deberían pasarse desde el controlador
                // Por ahora mostramos placeholders
                $total_registros = 0;
                $total_asistencias = 0;
                $ocupacion = 0;
                $cupoDisponible = $evento['cupo_maximo'];
                ?>
                
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="h4 text-canaco mb-0"><?php echo $total_registros; ?></div>
                            <small class="text-muted">Registrados</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="h4 text-canaco mb-0"><?php echo $cupoDisponible; ?></div>
                            <small class="text-muted">Disponibles</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="h4 text-canaco mb-0"><?php echo $ocupacion; ?>%</div>
                            <small class="text-muted">Ocupación</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="h4 text-canaco mb-0"><?php echo $total_asistencias; ?></div>
                            <small class="text-muted">Asistieron</small>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-grid gap-2">
                    <a href="<?php echo BASE_URL; ?>asistentes?evento=<?php echo $evento['id']; ?>" 
                       class="btn btn-outline-canaco btn-sm">
                        <i class="fas fa-users me-1"></i>
                        Ver Lista de Asistentes
                    </a>
                    
                    <?php if ($evento['estado'] === 'publicado'): ?>
                        <a href="<?php echo BASE_URL; ?>evento/<?php echo $evento['slug']; ?>" 
                           class="btn btn-outline-success btn-sm" target="_blank">
                            <i class="fas fa-external-link-alt me-1"></i>
                            Ver Página Pública
                        </a>
                    <?php endif; ?>
                    
                    <button type="button" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-download me-1"></i>
                        Exportar Lista
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Información
                </h5>
            </div>
            <div class="card-body">
                <div class="small text-muted">
                    <p><strong>Creado:</strong> <?php echo date('d/m/Y H:i', strtotime($evento['created_at'])); ?></p>
                    <p><strong>Modificado:</strong> <?php echo date('d/m/Y H:i', strtotime($evento['updated_at'])); ?></p>
                    <p class="mb-0"><strong>ID:</strong> <?php echo $evento['id']; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>