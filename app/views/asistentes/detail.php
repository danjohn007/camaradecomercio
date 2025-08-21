<?php 
$pageTitle = 'Detalle del Asistente'; 
include 'app/views/layouts/header.php'; 
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user me-2 text-canaco"></i>
        Detalle del Asistente
    </h1>
    <a href="<?php echo BASE_URL; ?>asistentes" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Volver a Asistentes
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Información del Asistente
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-canaco">Código Único</h6>
                        <p><code class="fs-6"><?php echo htmlspecialchars($asistente['codigo_unico']); ?></code></p>
                        
                        <h6 class="text-canaco">Nombre Completo</h6>
                        <p><?php echo htmlspecialchars($asistente['nombre_asistente']); ?></p>
                        
                        <h6 class="text-canaco">Email</h6>
                        <p><?php echo htmlspecialchars($asistente['email_asistente']); ?></p>
                        
                        <h6 class="text-canaco">Teléfono</h6>
                        <p><?php echo htmlspecialchars($asistente['telefono_asistente'] ?? 'No especificado'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-canaco">Tipo de Registrante</h6>
                        <p>
                            <span class="badge <?php echo $asistente['tipo_registrante'] === 'empresa' ? 'bg-primary' : 'bg-info'; ?>">
                                <?php echo $asistente['tipo_registrante'] === 'empresa' ? 'Empresa' : 'Invitado'; ?>
                            </span>
                        </p>
                        
                        <?php if ($asistente['tipo_registrante'] === 'empresa' && $asistente['razon_social']): ?>
                            <h6 class="text-canaco">Empresa</h6>
                            <p><?php echo htmlspecialchars($asistente['razon_social']); ?></p>
                            
                            <h6 class="text-canaco">RFC</h6>
                            <p><?php echo htmlspecialchars($asistente['rfc']); ?></p>
                        <?php endif; ?>
                        
                        <?php if ($asistente['tipo_registrante'] === 'invitado'): ?>
                            <?php if ($asistente['fecha_nacimiento']): ?>
                                <h6 class="text-canaco">Fecha de Nacimiento</h6>
                                <p><?php echo date('d/m/Y', strtotime($asistente['fecha_nacimiento'])); ?></p>
                            <?php endif; ?>
                            
                            <?php if ($asistente['ocupacion']): ?>
                                <h6 class="text-canaco">Ocupación</h6>
                                <p><?php echo htmlspecialchars($asistente['ocupacion']); ?></p>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <h6 class="text-canaco">Estado</h6>
                        <p>
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
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Información del Evento
                </h5>
            </div>
            <div class="card-body">
                <h6 class="text-canaco">Evento</h6>
                <p><?php echo htmlspecialchars($asistente['evento_titulo']); ?></p>
                
                <h6 class="text-canaco">Fecha y Hora</h6>
                <p>
                    <i class="fas fa-calendar me-2"></i>
                    <?php echo date('d/m/Y H:i', strtotime($asistente['fecha_evento'])); ?>
                </p>
                
                <h6 class="text-canaco">Ubicación</h6>
                <p>
                    <i class="fas fa-map-marker-alt me-2"></i>
                    <?php echo htmlspecialchars($asistente['ubicacion']); ?>
                </p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Fechas Importantes
                </h5>
            </div>
            <div class="card-body">
                <h6 class="text-canaco">Fecha de Registro</h6>
                <p>
                    <i class="fas fa-plus-circle me-2"></i>
                    <?php echo date('d/m/Y H:i', strtotime($asistente['created_at'])); ?>
                </p>
                
                <?php if ($asistente['fecha_asistencia']): ?>
                    <h6 class="text-canaco">Fecha de Asistencia</h6>
                    <p>
                        <i class="fas fa-check-circle me-2 text-success"></i>
                        <?php echo date('d/m/Y H:i', strtotime($asistente['fecha_asistencia'])); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cog me-2"></i>
                    Acciones
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if ($asistente['estado'] !== 'asistio'): ?>
                        <button class="btn btn-success" onclick="updateStatus(<?php echo $asistente['id']; ?>, 'asistio')">
                            <i class="fas fa-check me-2"></i>
                            Marcar como Asistió
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($asistente['estado'] !== 'no_asistio'): ?>
                        <button class="btn btn-warning" onclick="updateStatus(<?php echo $asistente['id']; ?>, 'no_asistio')">
                            <i class="fas fa-times me-2"></i>
                            Marcar como No Asistió
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($asistente['estado'] !== 'cancelado'): ?>
                        <button class="btn btn-danger" onclick="updateStatus(<?php echo $asistente['id']; ?>, 'cancelado')">
                            <i class="fas fa-ban me-2"></i>
                            Cancelar Registro
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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
</script>

<?php include 'app/views/layouts/footer.php'; ?>