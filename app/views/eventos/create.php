<?php $pageTitle = 'Crear Evento'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2 text-canaco"></i>
        Crear Nuevo Evento
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
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
                                   placeholder="Ej: Foro de Innovación Empresarial 2024">
                        </div>
                        
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción *</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required
                                      placeholder="Describe los detalles del evento, agenda, ponentes, objetivos..."></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="fecha_evento" class="form-label">Fecha y Hora *</label>
                            <input type="datetime-local" class="form-control" id="fecha_evento" name="fecha_evento" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="cupo_maximo" class="form-label">Cupo Máximo *</label>
                            <input type="number" class="form-control" id="cupo_maximo" name="cupo_maximo" 
                                   min="1" max="10000" required placeholder="200">
                        </div>
                        
                        <div class="col-12">
                            <label for="ubicacion" class="form-label">Ubicación *</label>
                            <textarea class="form-control" id="ubicacion" name="ubicacion" rows="2" required
                                      placeholder="Dirección completa del evento"></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="costo" class="form-label">Costo del Evento</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="costo" name="costo" 
                                       min="0" step="0.01" placeholder="0.00">
                            </div>
                            <div class="form-text">Dejar en 0 para eventos gratuitos</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="tipo_publico" class="form-label">Tipo de Público *</label>
                            <select class="form-select" id="tipo_publico" name="tipo_publico" required>
                                <option value="todos">Todos (Empresas e Invitados)</option>
                                <option value="empresas">Solo Empresas</option>
                                <option value="invitados">Solo Invitados</option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label for="imagen_banner" class="form-label">Imagen/Banner del Evento</label>
                            <input type="file" class="form-control" id="imagen_banner" name="imagen_banner" 
                                   accept="image/jpeg,image/png,image/gif">
                            <div class="form-text">Formato: JPG, PNG o GIF. Tamaño máximo: 5MB. Resolución recomendada: 1200x600px</div>
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
                            <label for="estado" class="form-label">Estado Inicial</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="borrador" selected>Borrador (no visible al público)</option>
                                <option value="publicado">Publicado (visible al público)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="<?php echo BASE_URL; ?>eventos" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i>
                    Cancelar
                </a>
                <div>
                    <button type="submit" name="estado" value="borrador" class="btn btn-outline-canaco me-2">
                        <i class="fas fa-save me-1"></i>
                        Guardar como Borrador
                    </button>
                    <button type="submit" name="estado" value="publicado" class="btn btn-canaco">
                        <i class="fas fa-check-circle me-1"></i>
                        Guardar y Publicar
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>
                    Consejos
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-1"></i> Título del evento</h6>
                    <p class="small mb-0">Usa un título claro y descriptivo que indique el tipo de evento y su objetivo principal.</p>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="fas fa-camera me-1"></i> Imagen del evento</h6>
                    <p class="small mb-0">Una imagen atractiva aumenta significativamente el registro. Usa colores corporativos de CANACO.</p>
                </div>
                
                <div class="alert alert-success">
                    <h6><i class="fas fa-users me-1"></i> Cupo del evento</h6>
                    <p class="small mb-0">Define un cupo realista considerando el espacio disponible y la demanda esperada.</p>
                </div>
                
                <hr>
                
                <h6 class="text-canaco">¿Qué sucede después?</h6>
                <ul class="small text-muted">
                    <li>Se generará un enlace público único</li>
                    <li>Los usuarios podrán registrarse automáticamente</li>
                    <li>Recibirás notificaciones de nuevos registros</li>
                    <li>Podrás exportar listas de asistentes</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Establecer fecha mínima como mañana
    const fechaInput = document.getElementById('fecha_evento');
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    fechaInput.min = tomorrow.toISOString().slice(0, 16);
    
    // Auto-generar slug en vista previa (opcional)
    const tituloInput = document.getElementById('titulo');
    tituloInput.addEventListener('input', function() {
        // Aquí podrías mostrar una vista previa del slug/URL
    });
});
</script>