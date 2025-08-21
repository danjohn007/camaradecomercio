<?php 
$pageTitle = 'Registro de Empresa - ' . htmlspecialchars($evento['titulo']); 
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
                                           name="email" required placeholder="Ingresa tu correo electrónico">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="puesto" class="form-label">Usted es *</label>
                                    <select class="form-select form-control-lg" id="puesto" name="puesto" required>
                                        <option value="">Seleccione su puesto</option>
                                        <option value="Dueño de la empresa">Dueño de la empresa</option>
                                        <option value="Director General">Director General</option>
                                        <option value="Gerente General">Gerente General</option>
                                        <option value="Gerente de Ventas">Gerente de Ventas</option>
                                        <option value="Gerente de Marketing">Gerente de Marketing</option>
                                        <option value="Gerente de Operaciones">Gerente de Operaciones</option>
                                        <option value="Coordinador">Coordinador</option>
                                        <option value="Empleado">Empleado</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label">Teléfono (opcional)</label>
                                    <input type="tel" class="form-control form-control-lg" id="telefono" 
                                           name="telefono" placeholder="10 dígitos">
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
                                
                                <div class="col-md-6">
                                    <label for="aniversario" class="form-label">Aniversario (opcional)</label>
                                    <input type="date" class="form-control form-control-lg" id="aniversario" 
                                           name="aniversario">
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
    // Note: RFC search is handled globally by app.js
    // Just setup form-specific validations here
    
    // Validación de RFC en tiempo real
    const rfcInput = document.getElementById('rfc');
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
    
    // Formato de teléfonos
    document.querySelectorAll('input[type="tel"]').forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    });
});
</script>