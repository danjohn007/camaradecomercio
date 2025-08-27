<?php 
$pageTitle = 'Registro de Invitado - ' . htmlspecialchars($evento['titulo']); 
$telefono = $_GET['telefono'] ?? '';
$email = $_GET['email'] ?? '';
$rfc = $_GET['rfc'] ?? '';
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
                                           name="email" required placeholder="Ingresa tu correo electrónico"
                                           value="<?php echo htmlspecialchars($email); ?>"
                                           data-event-slug="<?php echo $evento['slug']; ?>">
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
                                    <label for="ocupacion" class="form-label">Usted es *</label>
                                    <select class="form-select form-control-lg" id="ocupacion" name="ocupacion" required>
                                        <option value="">Seleccione una opción</option>
                                        <option value="Dueño o Representante Legal">Dueño o Representante Legal</option>
                                        <option value="Socio o Accionista">Socio o Accionista</option>
                                        <option value="Colaborador/Empleado">Colaborador/Empleado</option>
                                        <option value="Funcionario de Gobierno">Funcionario de Gobierno</option>
                                        <option value="Invitado General">Invitado General</option>
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

<!-- Modal para usuario existente -->
<div class="modal fade" id="existingUserModal" tabindex="-1" aria-labelledby="existingUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="existingUserModalLabel">
                    <i class="fas fa-user-check me-2"></i>
                    Usuario encontrado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Encontramos un registro previo con estos datos. ¿Qué desea hacer?
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-edit text-primary" style="font-size: 2rem;"></i>
                                <h6 class="mt-3">Actualizar Información</h6>
                                <p class="text-muted small">Modificar mis datos personales antes de obtener el boleto</p>
                                <button type="button" class="btn btn-primary" onclick="updateUserInfo()">
                                    <i class="fas fa-edit me-2"></i>
                                    Actualizar datos
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-ticket-alt text-success" style="font-size: 2rem;"></i>
                                <h6 class="mt-3">Solicitar Boleto</h6>
                                <p class="text-muted small">Usar mis datos actuales y obtener el boleto directamente</p>
                                <button type="button" class="btn btn-success" onclick="requestTicketDirectly()">
                                    <i class="fas fa-ticket-alt me-2"></i>
                                    Obtener boleto
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">
                        <strong>Datos encontrados:</strong>
                        <div id="foundUserData" class="mt-2"></div>
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const telefonoInput = document.getElementById('telefono');
    const emailInput = document.getElementById('email');
    const eventSlug = telefonoInput.dataset.eventSlug;
    
    // Auto-search on page load if parameters are provided
    const urlParams = new URLSearchParams(window.location.search);
    const phoneParam = urlParams.get('telefono');
    const emailParam = urlParams.get('email');
    const rfcParam = urlParams.get('rfc');
    
    // Perform automatic search based on URL parameters
    if (phoneParam && phoneParam.length >= 10) {
        CANACO.registration.searchByPhone(phoneParam, eventSlug);
    } else if (emailParam && emailParam.length > 5) {
        CANACO.registration.searchByEmail(emailParam, eventSlug);
    } else if (rfcParam && rfcParam.length >= 12) {
        // For RFC, search in company database but allow registration as guest
        CANACO.registration.searchByRFC(rfcParam, eventSlug);
    }
    
    // Auto-buscar datos cuando se ingrese el teléfono
    let timeoutPhone;
    telefonoInput.addEventListener('input', function() {
        // Solo números
        this.value = this.value.replace(/\D/g, '');
        
        clearTimeout(timeoutPhone);
        timeoutPhone = setTimeout(() => {
            if (this.value.length >= 10 && CANACO.validation.validatePhone(this.value)) {
                CANACO.registration.searchByPhone(this.value, eventSlug);
            }
        }, 500);
    });
    
    // Auto-buscar datos cuando se ingrese el email
    let timeoutEmail;
    emailInput.addEventListener('input', function() {
        clearTimeout(timeoutEmail);
        timeoutEmail = setTimeout(() => {
            if (this.value.length > 5 && CANACO.validation.validateEmail(this.value)) {
                CANACO.registration.searchByEmail(this.value, eventSlug);
            }
        }, 800);
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
    
    // Función para mostrar/ocultar campos según ocupación
    function toggleFieldsVisibility(ocupacion) {
        const allOptionalFields = [
            'cargo_gubernamental'
        ];
        
        // Obtener todos los campos opcionales
        const fieldsToHideElements = allOptionalFields.map(id => {
            const element = document.getElementById(id);
            return element ? element.closest('.col-md-6') || element.parentNode : null;
        }).filter(el => el !== null);
        
        if (ocupacion === 'Funcionario de Gobierno' || ocupacion === 'Invitado General') {
            // Para Funcionario de Gobierno e Invitado General, ocultar todos los campos adicionales
            fieldsToHideElements.forEach(el => {
                el.style.display = 'none';
                const input = el.querySelector('input, select');
                if (input) input.required = false;
            });
            
            // Para Funcionario de Gobierno, mostrar únicamente campo de cargo gubernamental
            if (ocupacion === 'Funcionario de Gobierno') {
                const cargoDiv = document.getElementById('cargo_gubernamental').parentNode;
                cargoDiv.style.display = 'block';
                document.getElementById('cargo_gubernamental').required = true;
            }
        } else {
            // Para otros casos, mostrar todos los campos
            fieldsToHideElements.forEach(el => {
                el.style.display = 'block';
            });
            
            // Ocultar cargo gubernamental para no funcionarios
            const cargoDiv = document.getElementById('cargo_gubernamental').parentNode;
            cargoDiv.style.display = 'none';
            document.getElementById('cargo_gubernamental').required = false;
        }
    }
    
    // Mostrar/ocultar campos según ocupación
    const ocupacionSelect = document.getElementById('ocupacion');
    const cargoGubernamentalDiv = document.getElementById('cargo_gubernamental').parentNode;
    
    ocupacionSelect.addEventListener('change', function() {
        toggleFieldsVisibility(this.value);
    });
    
    // Inicializar visibilidad en carga de página
    if (ocupacionSelect.value) {
        toggleFieldsVisibility(ocupacionSelect.value);
    }
    
    // Función para actualizar información del usuario
    window.updateUserInfo = function() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('existingUserModal'));
        modal.hide();
        // Los campos ya están pre-llenados, el usuario puede editarlos
        CANACO.utils.showAlert('Puede actualizar sus datos y luego enviar el formulario', 'info');
    };
    
    // Función para solicitar boleto directamente
    window.requestTicketDirectly = function() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('existingUserModal'));
        modal.hide();
        
        // Enviar formulario automáticamente con los datos existentes
        const form = document.querySelector('form[action*="registro-invitado"]');
        if (form) {
            // Mostrar confirmación antes de enviar
            if (confirm('¿Confirma que desea obtener el boleto con los datos mostrados?')) {
                form.submit();
            }
        }
    };
    
    // Función para mostrar modal de usuario existente
    window.showExistingUserModal = function(userData) {
        const foundUserDataDiv = document.getElementById('foundUserData');
        foundUserDataDiv.innerHTML = `
            <div class="bg-light p-2 rounded">
                <strong>Nombre:</strong> ${userData.nombre_completo || 'N/A'}<br>
                <strong>Email:</strong> ${userData.email || 'N/A'}<br>
                <strong>Teléfono:</strong> ${userData.telefono || 'N/A'}<br>
                <strong>Ocupación:</strong> ${userData.ocupacion || 'N/A'}
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('existingUserModal'));
        modal.show();
    };
});
</script>