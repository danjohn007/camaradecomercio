<?php 
$pageTitle = 'Registro de Invitado - ' . htmlspecialchars($evento['titulo']); 
$telefono = $_GET['telefono'] ?? '';
?>

<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <div class="container">
        <!-- Header con información del evento -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-canaco text-white">
                    <div class="card-body text-center">
                        <h2><?php echo htmlspecialchars($evento['titulo']); ?></h2>
                        <p class="mb-0">
                            <i class="fas fa-calendar me-2"></i>
                            <?php echo date('d/m/Y H:i', strtotime($evento['fecha_evento'])); ?>
                            |
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <?php echo htmlspecialchars($evento['ubicacion']); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-canaco text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Por favor ingresa la siguiente información para poder enviar su boleto:
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo BASE_URL; ?>api/registro-invitado" data-validate="true">
                            <input type="hidden" name="evento_slug" value="<?php echo $evento['slug']; ?>">
                            
                            <h4 class="text-canaco mb-3">Datos generales:</h4>
                            
                            <!-- Datos del invitado -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombre_completo" class="form-label">Nombre completo *</label>
                                    <input type="text" class="form-control form-control-lg" id="nombre_completo" 
                                           name="nombre_completo" required placeholder="Ingresa tu nombre completo">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Correo electrónico *</label>
                                    <input type="email" class="form-control form-control-lg" id="email" 
                                           name="email" required placeholder="Ingresa tu correo electrónico">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label">WhatsApp *</label>
                                    <input type="tel" class="form-control form-control-lg" id="telefono" 
                                           name="telefono" required maxlength="10" 
                                           value="<?php echo htmlspecialchars($telefono); ?>"
                                           data-event-slug="<?php echo $evento['slug']; ?>"
                                           placeholder="Teléfono (10 dígitos)">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento (opcional)</label>
                                    <input type="date" class="form-control form-control-lg" id="fecha_nacimiento" 
                                           name="fecha_nacimiento">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="ocupacion" class="form-label">Usted es *</label>
                                    <select class="form-select form-control-lg" id="ocupacion" name="ocupacion" required>
                                        <option value="">Seleccione una opción</option>
                                        <option value="Funcionario de Gobierno">Funcionario de Gobierno</option>
                                        <option value="Empresario">Empresario</option>
                                        <option value="Empleado">Empleado</option>
                                        <option value="Estudiante">Estudiante</option>
                                        <option value="Consultor">Consultor</option>
                                        <option value="Académico">Académico</option>
                                        <option value="Periodista">Periodista</option>
                                        <option value="Invitado especial">Invitado especial</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="cargo_gubernamental" class="form-label">Cargo gubernamental</label>
                                    <select class="form-select form-control-lg" id="cargo_gubernamental" name="cargo_gubernamental">
                                        <option value="">Seleccione si aplica</option>
                                        <option value="Estatal">Estatal</option>
                                        <option value="Federal">Federal</option>
                                        <option value="Municipal">Municipal</option>
                                        <option value="No aplica">No aplica</option>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Importante:</strong> Recibirás tu boleto con código QR por correo electrónico. 
                                        Asegúrate de proporcionar un email válido.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-canaco btn-lg">
                                    <i class="fas fa-ticket-alt me-2"></i>
                                    Obtener invitación
                                </button>
                                <a href="<?php echo BASE_URL; ?>evento/<?php echo $evento['slug']; ?>" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Regresar al evento
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Vista previa del boleto -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-canaco">
                                    <i class="fas fa-ticket-alt me-2"></i>
                                    Vista previa de tu boleto
                                </h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <div class="bg-light p-3 rounded">
                                        <i class="fas fa-qrcode" style="font-size: 3rem; color: #ccc;"></i>
                                    </div>
                                </div>
                                <h6>Código QR único</h6>
                                <p class="small text-muted mb-0">
                                    Se generará automáticamente al completar el registro
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-info">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-canaco">
                                    <i class="fas fa-info-circle me-2"></i>
                                    ¿Qué incluye tu registro?
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Acceso al evento
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Código QR único
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Confirmación por email
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Recordatorio del evento
                                    </li>
                                    <?php if ($evento['costo'] == 0): ?>
                                    <li class="mb-0">
                                        <i class="fas fa-gift text-success me-2"></i>
                                        <strong>Evento gratuito</strong>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const telefonoInput = document.getElementById('telefono');
    const eventSlug = telefonoInput.dataset.eventSlug;
    
    // Auto-buscar datos cuando se ingrese el teléfono
    let timeout;
    telefonoInput.addEventListener('input', function() {
        // Solo números
        this.value = this.value.replace(/\D/g, '');
        
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            if (this.value.length >= 10 && CANACO.validation.validatePhone(this.value)) {
                CANACO.registration.searchByPhone(this.value, eventSlug);
            }
        }, 500);
    });
    
    // Validación de teléfono en tiempo real
    telefonoInput.addEventListener('blur', function() {
        if (this.value && !CANACO.validation.validatePhone(this.value)) {
            this.classList.add('is-invalid');
            let feedback = this.parentNode.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                this.parentNode.appendChild(feedback);
            }
            feedback.textContent = 'Teléfono inválido. Debe tener 10 dígitos.';
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    // Mostrar/ocultar campo de cargo gubernamental
    const ocupacionSelect = document.getElementById('ocupacion');
    const cargoGubernamentalDiv = document.getElementById('cargo_gubernamental').parentNode;
    
    ocupacionSelect.addEventListener('change', function() {
        if (this.value === 'Funcionario de Gobierno') {
            cargoGubernamentalDiv.style.display = 'block';
            document.getElementById('cargo_gubernamental').required = true;
        } else {
            cargoGubernamentalDiv.style.display = 'block'; // Mantener visible según mockup
            document.getElementById('cargo_gubernamental').required = false;
        }
    });
});
</script>