<?php 
$pageTitle = 'Registro de Empresa - ' . htmlspecialchars($evento['titulo']); 
$rfc = $_GET['rfc'] ?? '';
$telefono = $_GET['telefono'] ?? '';
$email = $_GET['email'] ?? '';
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
                            <i class="fas fa-building me-2"></i>
                            Datos generales:
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo BASE_URL; ?>api/registro-empresa" data-validate="true">
                            <input type="hidden" name="evento_slug" value="<?php echo $evento['slug']; ?>">
                            
                            <!-- Datos del representante -->
                            <div class="row g-3 mb-4">
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
                                    <label for="puesto" class="form-label">Usted es *</label>
                                    <select class="form-select form-control-lg" id="puesto" name="puesto" required>
                                        <option value="">Seleccione su puesto</option>
                                        <option value="Dueño o Representante Legal">Dueño o Representante Legal</option>
                                        <option value="Socio o Accionista">Socio o Accionista</option>
                                        <option value="Colaborador/Empleado">Colaborador/Empleado</option>
                                        <option value="Funcionario de Gobierno">Funcionario de Gobierno</option>
                                        <option value="Invitado General">Invitado General</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label">Teléfono (opcional)</label>
                                    <input type="tel" class="form-control form-control-lg" id="telefono" 
                                           name="telefono" placeholder="10 dígitos"
                                           value="<?php echo htmlspecialchars($telefono); ?>"
                                           data-event-slug="<?php echo $evento['slug']; ?>">
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <h4 class="text-canaco mb-3">Datos de la empresa:</h4>
                            
                            <!-- Datos de la empresa -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="rfc" class="form-label">RFC *</label>
                                    <input type="text" class="form-control form-control-lg" id="rfc" 
                                           name="rfc" required maxlength="13" style="text-transform: uppercase;"
                                           value="<?php echo htmlspecialchars($rfc); ?>" 
                                           data-event-slug="<?php echo $evento['slug']; ?>"
                                           placeholder="RFC de la empresa (12-13 caracteres)">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="nombre_comercial" class="form-label">Nombre comercial *</label>
                                    <input type="text" class="form-control form-control-lg" id="nombre_comercial" 
                                           name="nombre_comercial" required placeholder="Nombre comercial de su empresa">
                                </div>
                                
                                <div class="col-12">
                                    <label for="razon_social" class="form-label">Razón social *</label>
                                    <input type="text" class="form-control form-control-lg" id="razon_social" 
                                           name="razon_social" required placeholder="Razón social de la empresa">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="direccion_comercial" class="form-label">Dirección comercial</label>
                                    <textarea class="form-control" id="direccion_comercial" name="direccion_comercial" 
                                              rows="2" placeholder="Dirección comercial de la empresa"></textarea>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="giro_comercial" class="form-label">Venden *</label>
                                    <select class="form-select form-control-lg" id="giro_comercial" name="giro_comercial" required>
                                        <option value="">Seleccione el giro</option>
                                        <option value="Productos">Productos</option>
                                        <option value="Servicios">Servicios</option>
                                        <option value="Productos y Servicios">Productos y Servicios</option>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label for="direccion_fiscal" class="form-label">Dirección fiscal</label>
                                    <textarea class="form-control" id="direccion_fiscal" name="direccion_fiscal" 
                                              rows="2" placeholder="Dirección fiscal de la empresa"></textarea>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="telefono_oficina" class="form-label">Teléfono de la oficina (opcional)</label>
                                    <input type="tel" class="form-control form-control-lg" id="telefono_oficina" 
                                           name="telefono_oficina" placeholder="Número de teléfono de su empresa">
                                </div>
                                
                                <div class="col-12">
                                    <label for="numero_afiliacion" class="form-label">Número de afiliación (opcional)</label>
                                    <input type="text" class="form-control form-control-lg" id="numero_afiliacion" 
                                           name="numero_afiliacion" placeholder="Número de afiliación de la empresa">
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="consejero_camara" 
                                               name="consejero_camara">
                                        <label class="form-check-label" for="consejero_camara">
                                            Soy consejero a la Cámara de Comercio de Querétaro
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="acepta_terminos" 
                                               name="acepta_terminos" required>
                                        <label class="form-check-label" for="acepta_terminos">
                                            Acepto los términos y condiciones del sistema
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-canaco btn-lg">
                                    <i class="fas fa-ticket-alt me-2"></i>
                                    Obtener boleto
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
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rfcInput = document.getElementById('rfc');
    const emailInput = document.getElementById('email');
    const telefonoInput = document.getElementById('telefono');
    const eventSlug = rfcInput.dataset.eventSlug;
    
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
        CANACO.registration.searchByRFC(rfcParam, eventSlug);
    }
    
    // Auto-buscar datos cuando se ingrese el RFC
    let timeout;
    rfcInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
        
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            if (this.value.length >= 12 && CANACO.validation.validateRFC(this.value)) {
                CANACO.registration.searchByRFC(this.value, eventSlug);
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
    
    // Validación de RFC en tiempo real
    rfcInput.addEventListener('blur', function() {
        if (this.value && !CANACO.validation.validateRFC(this.value)) {
            this.classList.add('is-invalid');
            let feedback = this.parentNode.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                this.parentNode.appendChild(feedback);
            }
            feedback.textContent = 'RFC inválido. Debe tener 12 o 13 caracteres.';
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    // Función para mostrar/ocultar campos según puesto/ocupación
    function toggleFieldsVisibility(puesto) {
        // Campos de empresa que se ocultan para Funcionario de Gobierno e Invitado General
        const empresaFieldIds = [
            'rfc', 'nombre_comercial', 'razon_social', 'direccion_comercial', 
            'giro_comercial', 'direccion_fiscal', 'telefono_oficina', 
            'numero_afiliacion', 'consejero_camara'
        ];
        
        const empresaFields = empresaFieldIds.map(id => {
            const element = document.getElementById(id);
            return element ? element.closest('.col-md-6, .col-12') || element.parentNode : null;
        }).filter(el => el !== null);
        
        // También buscar el título de "Datos de la empresa"
        const empresaSection = document.querySelector('h4.text-canaco');
        const empresaHr = empresaSection ? empresaSection.nextElementSibling : null;
        
        if (puesto === 'Funcionario de Gobierno' || puesto === 'Invitado General') {
            // Ocultar todos los campos de empresa
            empresaFields.forEach(el => {
                el.style.display = 'none';
                const input = el.querySelector('input, select, textarea');
                if (input) {
                    input.required = false;
                    input.value = '';
                }
            });
            
            // Ocultar título de sección de empresa
            if (empresaSection) empresaSection.style.display = 'none';
            if (empresaHr) empresaHr.style.display = 'none';
            
            // Mantener solo campos básicos: nombre, email, teléfono
            CANACO.utils.showAlert('Formulario simplificado: Solo necesita completar nombre, correo y WhatsApp.', 'info');
        } else {
            // Mostrar todos los campos de empresa
            empresaFields.forEach(el => {
                el.style.display = 'block';
            });
            
            // Mostrar título de sección de empresa
            if (empresaSection) empresaSection.style.display = 'block';
            if (empresaHr) empresaHr.style.display = 'block';
            
            // Restaurar campos requeridos
            document.getElementById('rfc').required = true;
            document.getElementById('nombre_comercial').required = true;
            document.getElementById('razon_social').required = true;
            document.getElementById('giro_comercial').required = true;
        }
    }
    
    // Event listener para cambio de puesto
    const puestoSelect = document.getElementById('puesto');
    if (puestoSelect) {
        puestoSelect.addEventListener('change', function() {
            toggleFieldsVisibility(this.value);
        });
        
        // Aplicar configuración inicial si ya hay un valor seleccionado
        if (puestoSelect.value) {
            toggleFieldsVisibility(puestoSelect.value);
        }
    }
    
    // Formato de teléfonos
    document.querySelectorAll('input[type="tel"]').forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    });
});
</script>