<?php $pageTitle = htmlspecialchars($evento['titulo']); ?>

<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row">
            <!-- Banner del evento (lado izquierdo) -->
            <div class="col-lg-6 mb-4">
                <div class="event-banner">
                    <?php if ($evento['imagen_banner']): ?>
                        <img src="<?php echo BASE_URL . UPLOAD_PATH; ?>eventos/<?php echo $evento['imagen_banner']; ?>" 
                             class="img-fluid w-100" alt="<?php echo htmlspecialchars($evento['titulo']); ?>"
                             style="border-radius: 10px; height: 500px; object-fit: cover;">
                    <?php else: ?>
                        <!-- Banner por defecto similar al de la imagen -->
                        <div class="bg-canaco text-white p-5 text-center" style="border-radius: 10px; height: 500px; position: relative; overflow: hidden;">
                            <div style="position: absolute; top: 0; right: 0; width: 100%; height: 100%; background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><polygon fill=\"%23ffffff\" fill-opacity=\"0.1\" points=\"0,0 100,0 70,100 0,100\"/></svg>') no-repeat; background-size: cover;"></div>
                            <div class="position-relative h-100 d-flex flex-column justify-content-center">
                                <div class="mb-4">
                                    <i class="fas fa-building" style="font-size: 4rem; opacity: 0.9;"></i>
                                </div>
                                <h2 class="mb-3">CÁMARA DE COMERCIO</h2>
                                <h3 class="mb-3">SERVICIOS Y TURISMO DE QUERÉTARO</h3>
                                <hr class="my-4" style="border-color: rgba(255,255,255,0.3);">
                                <h4 class="mb-3">Te invita al encuentro de negocios y cocktail</h4>
                                <p class="lead mb-4">donde presentaremos la plataforma tecnológica:</p>
                                <div class="badge bg-light text-dark px-3 py-2" style="font-size: 1.1rem;">
                                    enlacecanaco.org
                                </div>
                                <p class="mt-3 small" style="opacity: 0.8;">PROGRESSIVE WEB APP</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Panel de información y registro (lado derecho) -->
            <div class="col-lg-6">
                <!-- Panel de información del evento -->
                <div class="event-info-panel mb-4">
                    <h1 class="mb-3"><?php echo htmlspecialchars($evento['titulo']); ?></h1>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>ID del evento:</strong> <?php echo $evento['id']; ?>
                        </div>
                        <div class="col-6">
                            <strong>Boletos disponibles:</strong> <?php echo number_format($cupoDisponible); ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Descripción del evento:</strong>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($evento['descripcion'])); ?></p>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Fecha:</strong> <?php echo date('Y-m-d', strtotime($evento['fecha_evento'])); ?>
                        </div>
                        <div class="col-6">
                            <strong>Hora del evento:</strong> <?php echo date('H:i:s', strtotime($evento['fecha_evento'])); ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Dirección del evento:</strong>
                        <p class="mb-0"><?php echo htmlspecialchars($evento['ubicacion']); ?></p>
                    </div>
                    
                    <div class="mb-0">
                        <strong>Acceso del público:</strong> 
                        <?php 
                        switch($evento['tipo_publico']) {
                            case 'empresas':
                                echo 'Solo empresas';
                                break;
                            case 'invitados':
                                echo 'Solo invitados';
                                break;
                            default:
                                echo 'Todos';
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Formulario de registro -->
                <div class="registration-form">
                    <?php if ($cupoDisponible <= 0): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-times-circle text-danger" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 text-danger">Evento Completo</h4>
                            <p class="text-muted">Lo sentimos, ya no hay cupos disponibles para este evento.</p>
                        </div>
                    <?php else: ?>
                        <h3 class="mb-4 text-canaco">Buscar registro existente</h3>
                        
                        <form id="registrationSearchForm">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="input-group input-group-lg">
                                        <input type="text" class="form-control form-control-lg" id="searchInput" 
                                               placeholder="Ingrese su teléfono o correo electrónico" 
                                               data-event-slug="<?php echo $evento['slug']; ?>">
                                        <button type="button" class="btn btn-canaco" id="btnBuscar">
                                            <i class="fas fa-search me-2"></i>
                                            Buscar
                                        </button>
                                    </div>
                                    <div class="form-text">
                                        Si ya tiene registros previos, se pre-cargarán sus datos automáticamente
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <?php if ($evento['tipo_publico'] === 'todos' || $evento['tipo_publico'] === 'empresas'): ?>
                                        <a href="<?php echo BASE_URL; ?>registro/empresa/<?php echo $evento['slug']; ?>" 
                                           class="btn btn-outline-canaco btn-lg w-100">
                                            <i class="fas fa-building me-2"></i>
                                            Registro de Empresa
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <?php if ($evento['tipo_publico'] === 'todos' || $evento['tipo_publico'] === 'invitados'): ?>
                                        <a href="<?php echo BASE_URL; ?>registro/invitado/<?php echo $evento['slug']; ?>" 
                                           class="btn btn-outline-canaco btn-lg w-100">
                                            <i class="fas fa-user me-2"></i>
                                            Registro de Invitado
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                        
                        <div class="mt-4 text-center text-muted">
                            <small>
                                <i class="fas fa-shield-alt me-1"></i>
                                Tus datos están protegidos y solo serán utilizados para este evento
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Información adicional -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="text-canaco">¿Necesitas ayuda?</h5>
                        <p class="mb-3">
                            Si tienes dudas sobre el registro o el evento, contáctanos al 
                            <strong>+52 442 123 4567</strong> o escríbenos a 
                            <strong>eventos@canaco.org.mx</strong>
                        </p>
                        <div>
                            <a href="<?php echo BASE_URL; ?>historial-boletos" class="btn btn-outline-info">
                                <i class="fas fa-history me-2"></i>
                                Ver historial de mis boletos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const btnBuscar = document.getElementById('btnBuscar');
    
    if (!searchInput || !btnBuscar) return;
    
    // Auto-search functionality with timeout
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const query = this.value.trim();
            if (query.length >= 10) { // Minimum length for phone or reasonable email
                performSearch(query);
            }
        }, 800); // Wait 800ms after user stops typing
    });
    
    // Manual search when button is clicked
    btnBuscar.addEventListener('click', function() {
        const query = searchInput.value.trim();
        if (query.length < 3) {
            alert('Por favor ingrese al menos 3 caracteres para buscar');
            return;
        }
        performSearch(query);
    });
    
    // Enter key triggers search
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            btnBuscar.click();
        }
    });
    
    function performSearch(query) {
        const eventSlug = searchInput.dataset.eventSlug;
        
        // Show loading state
        const originalText = btnBuscar.innerHTML;
        btnBuscar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Buscando...';
        btnBuscar.disabled = true;
        
        // Determine if query is email or phone
        const isEmail = query.includes('@');
        const isPhone = /^[0-9]{10}$/.test(query.replace(/\D/g, ''));
        
        if (isEmail) {
            // Search by email (could be either company or guest)
            searchByEmail(query, eventSlug);
        } else if (isPhone) {
            // Search by phone (guest registration)
            const phoneOnly = query.replace(/\D/g, '');
            window.location.href = `<?php echo BASE_URL; ?>registro/invitado/${eventSlug}?telefono=${encodeURIComponent(phoneOnly)}`;
        } else if (query.length >= 12) {
            // Assume it's RFC (company registration)
            window.location.href = `<?php echo BASE_URL; ?>registro/empresa/${eventSlug}?rfc=${encodeURIComponent(query.toUpperCase())}`;
        } else {
            alert('Por favor ingrese un teléfono (10 dígitos), correo electrónico o RFC válido');
        }
        
        // Restore button state
        setTimeout(() => {
            btnBuscar.innerHTML = originalText;
            btnBuscar.disabled = false;
        }, 2000);
    }
    
    function searchByEmail(email, eventSlug) {
        // Try to determine if email belongs to company or guest
        // For now, we'll default to guest registration with email pre-filled
        window.location.href = `<?php echo BASE_URL; ?>registro/invitado/${eventSlug}?email=${encodeURIComponent(email)}`;
    }
});
</script>