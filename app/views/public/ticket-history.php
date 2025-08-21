<?php 
$pageTitle = 'Historial de Boletos'; 
?>

<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <div class="container">
        <!-- Header -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-canaco text-white text-center">
                        <h3 class="mb-0">
                            <i class="fas fa-history me-2"></i>
                            Historial de Boletos
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-center mb-4">
                            Consulta todos los boletos emitidos con tu RFC o número de teléfono.
                        </p>
                        
                        <!-- Search Form -->
                        <form method="POST" action="<?php echo BASE_URL; ?>buscar-historial-boletos" data-validate="true">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="identifier_type" class="form-label">Buscar por:</label>
                                    <select class="form-select" name="identifier_type" id="identifier_type" required>
                                        <option value="">Seleccione una opción</option>
                                        <option value="rfc" <?php echo (isset($identifierType) && $identifierType === 'rfc') ? 'selected' : ''; ?>>RFC (Empresas)</option>
                                        <option value="telefono" <?php echo (isset($identifierType) && $identifierType === 'telefono') ? 'selected' : ''; ?>>Teléfono (Invitados)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="identifier" class="form-label">RFC o Teléfono:</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="identifier" 
                                           name="identifier"
                                           value="<?php echo isset($identifier) ? htmlspecialchars($identifier) : ''; ?>"
                                           placeholder="Ingrese su RFC o teléfono"
                                           required>
                                </div>
                            </div>
                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-canaco">
                                    <i class="fas fa-search me-2"></i>
                                    Buscar Historial
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (isset($searchPerformed) && $searchPerformed): ?>
        <!-- Results -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if (empty($tickets)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h5>No se encontraron boletos</h5>
                    <p class="mb-0">No hay boletos registrados con este <?php echo $identifierType === 'rfc' ? 'RFC' : 'teléfono'; ?>.</p>
                </div>
                <?php else: ?>
                <div class="card shadow">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-ticket-alt me-2"></i>
                            Boletos Encontrados (<?php echo count($tickets); ?>)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Evento</th>
                                        <th>Fecha del Evento</th>
                                        <th>Fecha de Registro</th>
                                        <th>Estado</th>
                                        <th>Asistencia</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tickets as $ticket): ?>
                                    <tr>
                                        <td>
                                            <div>
                                                <strong><?php echo htmlspecialchars($ticket['evento_titulo']); ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    <?php echo htmlspecialchars($ticket['ubicacion']); ?>
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php echo date('d/m/Y H:i', strtotime($ticket['fecha_evento'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo date('d/m/Y', strtotime($ticket['created_at'])); ?>
                                        </td>
                                        <td>
                                            <?php
                                            $estadoClass = match($ticket['estado']) {
                                                'registrado' => 'bg-primary',
                                                'confirmado' => 'bg-info',
                                                'asistio' => 'bg-success',
                                                'no_asistio' => 'bg-warning',
                                                'cancelado' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                            $estadoText = match($ticket['estado']) {
                                                'registrado' => 'Registrado',
                                                'confirmado' => 'Confirmado',
                                                'asistio' => 'Asistió',
                                                'no_asistio' => 'No Asistió',
                                                'cancelado' => 'Cancelado',
                                                default => 'Desconocido'
                                            };
                                            ?>
                                            <span class="badge <?php echo $estadoClass; ?>">
                                                <?php echo $estadoText; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($ticket['estado'] === 'asistio'): ?>
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    <?php echo date('d/m/Y H:i', strtotime($ticket['fecha_asistencia'])); ?>
                                                </span>
                                            <?php elseif ($ticket['estado'] === 'no_asistio'): ?>
                                                <span class="text-warning">
                                                    <i class="fas fa-times-circle me-1"></i>
                                                    No asistió
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">
                                                    <i class="fas fa-minus-circle me-1"></i>
                                                    Pendiente
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>registro/confirmacion/<?php echo $ticket['codigo_unico']; ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Ver boleto">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Back to home -->
        <div class="row justify-content-center mt-4">
            <div class="col-lg-8 text-center">
                <a href="<?php echo BASE_URL; ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-home me-2"></i>
                    Volver al Inicio
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const identifierType = document.getElementById('identifier_type');
    const identifierInput = document.getElementById('identifier');
    
    identifierType.addEventListener('change', function() {
        if (this.value === 'rfc') {
            identifierInput.placeholder = 'Ejemplo: ABC123456789';
            identifierInput.maxLength = 13;
        } else if (this.value === 'telefono') {
            identifierInput.placeholder = 'Ejemplo: 4421234567';
            identifierInput.maxLength = 10;
        } else {
            identifierInput.placeholder = 'Ingrese su RFC o teléfono';
            identifierInput.removeAttribute('maxLength');
        }
        identifierInput.value = '';
    });
    
    // Formato para RFC
    identifierInput.addEventListener('input', function() {
        if (identifierType.value === 'rfc') {
            this.value = this.value.toUpperCase();
        } else if (identifierType.value === 'telefono') {
            this.value = this.value.replace(/\D/g, '');
        }
    });
});
</script>