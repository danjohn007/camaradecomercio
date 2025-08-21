<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-qrcode me-2 text-canaco"></i>
        Validación QR
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo BASE_URL; ?>qr/historial" class="btn btn-outline-secondary">
                <i class="fas fa-history"></i> Historial
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Escáner QR -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-camera me-2"></i>
                    Escáner de Códigos QR
                </h5>
            </div>
            <div class="card-body">
                <!-- Botones de modo -->
                <div class="mb-3">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary active" id="cameraMode" onclick="switchMode('camera')">
                            <i class="fas fa-camera me-1"></i> Cámara
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="manualMode" onclick="switchMode('manual')">
                            <i class="fas fa-keyboard me-1"></i> Manual
                        </button>
                    </div>
                </div>
                
                <!-- Escáner de cámara -->
                <div id="cameraScanner" class="scanner-container">
                    <div id="scanner" class="mb-3">
                        <video id="video" class="w-100" style="max-height: 400px; border-radius: 8px; background: #000;"></video>
                        <canvas id="canvas" style="display: none;"></canvas>
                    </div>
                    
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-canaco" id="startCamera" onclick="startCamera()">
                            <i class="fas fa-play me-1"></i> Iniciar Cámara
                        </button>
                        <button type="button" class="btn btn-secondary d-none" id="stopCamera" onclick="stopCamera()">
                            <i class="fas fa-stop me-1"></i> Detener Cámara
                        </button>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Instrucciones:</strong> Coloca el código QR frente a la cámara. La validación se realizará automáticamente cuando se detecte un código válido.
                    </div>
                </div>
                
                <!-- Entrada manual -->
                <div id="manualScanner" class="d-none">
                    <div class="mb-3">
                        <label for="codigoManual" class="form-label">Código QR</label>
                        <input type="text" class="form-control form-control-lg" id="codigoManual" 
                               placeholder="Ingresa el código del QR" maxlength="20">
                        <div class="form-text">Ingresa el código de 8 dígitos del boleto</div>
                    </div>
                    
                    <button type="button" class="btn btn-canaco btn-lg" onclick="validateManualCode()">
                        <i class="fas fa-check me-1"></i> Validar Código
                    </button>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Modo manual:</strong> Útil cuando la cámara no está disponible o el código QR no se puede escanear.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Panel de información -->
    <div class="col-lg-4">
        <!-- Estadísticas del día -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2 text-canaco"></i>
                    Estadísticas de Hoy
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-success mb-1" id="validacionesHoy">-</h4>
                            <small class="text-muted">Validaciones</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info mb-1" id="eventosHoy">-</h4>
                        <small class="text-muted">Eventos</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Resultado de validación -->
        <div id="resultadoValidacion" class="d-none">
            <div class="card border-0 shadow-sm">
                <div class="card-header" id="resultadoHeader">
                    <h6 class="card-title mb-0" id="resultadoTitulo"></h6>
                </div>
                <div class="card-body" id="resultadoBody">
                    <!-- El contenido se llenará dinámicamente -->
                </div>
            </div>
        </div>
        
        <!-- Últimas validaciones -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-clock me-2 text-canaco"></i>
                    Últimas Validaciones
                </h6>
            </div>
            <div class="card-body" id="ultimasValidaciones">
                <div class="text-center text-muted py-3">
                    <i class="fas fa-hourglass-half"></i>
                    <p class="mb-0 mt-2">Cargando...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/@zxing/library@latest/umd/index.min.js"></script>

<script>
let codeReader = null;
let selectedDeviceId = null;
let isScanning = false;

// Estadísticas
async function loadStats() {
    try {
        const response = await fetch(`${CANACO.baseUrl}qr/stats`);
        const stats = await response.json();
        
        document.getElementById('validacionesHoy').textContent = stats.hoy || 0;
        document.getElementById('eventosHoy').textContent = stats.eventos_hoy?.length || 0;
        
        // Mostrar últimas validaciones
        const container = document.getElementById('ultimasValidaciones');
        if (stats.eventos_hoy && stats.eventos_hoy.length > 0) {
            container.innerHTML = stats.eventos_hoy.slice(0, 5).map(evento => `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <small class="fw-medium">${evento.titulo}</small>
                    </div>
                    <span class="badge bg-success">${evento.validaciones}</span>
                </div>
            `).join('');
        } else {
            container.innerHTML = `
                <div class="text-center text-muted py-3">
                    <i class="fas fa-inbox"></i>
                    <p class="mb-0 mt-2">No hay validaciones hoy</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error cargando estadísticas:', error);
    }
}

// Cambiar modo de escaneo
function switchMode(mode) {
    const cameraMode = document.getElementById('cameraMode');
    const manualMode = document.getElementById('manualMode');
    const cameraScanner = document.getElementById('cameraScanner');
    const manualScanner = document.getElementById('manualScanner');
    
    if (mode === 'camera') {
        cameraMode.classList.add('active');
        manualMode.classList.remove('active');
        cameraScanner.classList.remove('d-none');
        manualScanner.classList.add('d-none');
    } else {
        manualMode.classList.add('active');
        cameraMode.classList.remove('active');
        manualScanner.classList.remove('d-none');
        cameraScanner.classList.add('d-none');
        stopCamera();
    }
}

// Iniciar cámara
async function startCamera() {
    try {
        if (!codeReader) {
            codeReader = new ZXing.BrowserQRCodeReader();
        }
        
        const videoElement = document.getElementById('video');
        const startBtn = document.getElementById('startCamera');
        const stopBtn = document.getElementById('stopCamera');
        
        startBtn.disabled = true;
        startBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Iniciando...';
        
        // Obtener lista de cámaras
        const videoInputDevices = await ZXing.BrowserQRCodeReader.listVideoInputDevices();
        
        if (videoInputDevices.length === 0) {
            throw new Error('No se encontraron cámaras disponibles');
        }
        
        // Usar la primera cámara disponible
        selectedDeviceId = videoInputDevices[0].deviceId;
        
        // Iniciar escaneo
        isScanning = true;
        await codeReader.decodeFromVideoDevice(selectedDeviceId, videoElement, (result, error) => {
            if (result && isScanning) {
                // Código detectado
                validateQRCode(result.text);
            }
            
            if (error && !(error instanceof ZXing.NotFoundException)) {
                console.error('Error en el escáner:', error);
            }
        });
        
        startBtn.classList.add('d-none');
        stopBtn.classList.remove('d-none');
        
    } catch (error) {
        console.error('Error al iniciar la cámara:', error);
        CANACO.utils.showAlert('Error al acceder a la cámara: ' + error.message, 'danger');
        
        const startBtn = document.getElementById('startCamera');
        startBtn.disabled = false;
        startBtn.innerHTML = '<i class="fas fa-play me-1"></i> Iniciar Cámara';
    }
}

// Detener cámara
function stopCamera() {
    if (codeReader) {
        codeReader.reset();
        isScanning = false;
    }
    
    const startBtn = document.getElementById('startCamera');
    const stopBtn = document.getElementById('stopCamera');
    
    startBtn.classList.remove('d-none');
    startBtn.disabled = false;
    startBtn.innerHTML = '<i class="fas fa-play me-1"></i> Iniciar Cámara';
    stopBtn.classList.add('d-none');
}

// Validar código manual
function validateManualCode() {
    const codigo = document.getElementById('codigoManual').value.trim();
    
    if (!codigo) {
        CANACO.utils.showAlert('Por favor ingresa un código', 'warning');
        return;
    }
    
    if (!/^\d{8}$/.test(codigo)) {
        CANACO.utils.showAlert('El código debe tener 8 dígitos', 'warning');
        return;
    }
    
    validateQRCode(codigo);
}

// Validar código QR
async function validateQRCode(codigo) {
    try {
        // Mostrar indicador de carga
        showValidationResult('loading', 'Validando...', 'Verificando código QR...');
        
        const response = await fetch(`${CANACO.baseUrl}qr/validar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ codigo: codigo })
        });
        
        const data = await response.json();
        
        if (data.error) {
            showValidationResult('error', 'Error de Validación', data.error);
            return;
        }
        
        if (data.ya_validado) {
            showValidationResult('warning', 'Ya Validado', `
                <div class="mb-3">
                    <h6>${data.registro.nombre}</h6>
                    <p class="mb-1"><strong>Evento:</strong> ${data.registro.evento}</p>
                    <p class="mb-1"><strong>Email:</strong> ${data.registro.email}</p>
                    <p class="mb-0"><strong>Validado:</strong> ${new Date(data.fecha_asistencia).toLocaleString()}</p>
                </div>
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Este código ya fue validado anteriormente.
                </div>
            `);
        } else if (data.validado) {
            showValidationResult('success', 'Validación Exitosa', `
                <div class="mb-3">
                    <h6>${data.registro.nombre}</h6>
                    <p class="mb-1"><strong>Evento:</strong> ${data.registro.evento}</p>
                    <p class="mb-1"><strong>Email:</strong> ${data.registro.email}</p>
                    <p class="mb-0"><strong>Tipo:</strong> ${data.registro.tipo === 'empresa' ? 'Empresa' : 'Invitado'}</p>
                </div>
                <div class="alert alert-success mb-0">
                    <i class="fas fa-check-circle me-2"></i>
                    Asistencia registrada correctamente.
                </div>
            `);
            
            // Actualizar estadísticas
            loadStats();
            
            // Limpiar código manual si estaba en uso
            document.getElementById('codigoManual').value = '';
        }
        
    } catch (error) {
        console.error('Error al validar QR:', error);
        showValidationResult('error', 'Error de Conexión', 'No se pudo conectar con el servidor. Intenta nuevamente.');
    }
}

// Mostrar resultado de validación
function showValidationResult(type, title, content) {
    const container = document.getElementById('resultadoValidacion');
    const header = document.getElementById('resultadoHeader');
    const titleElement = document.getElementById('resultadoTitulo');
    const body = document.getElementById('resultadoBody');
    
    // Configurar estilos según el tipo
    const configs = {
        'loading': {
            headerClass: 'bg-info text-white',
            icon: 'fas fa-spinner fa-spin'
        },
        'success': {
            headerClass: 'bg-success text-white',
            icon: 'fas fa-check-circle'
        },
        'warning': {
            headerClass: 'bg-warning text-dark',
            icon: 'fas fa-exclamation-triangle'
        },
        'error': {
            headerClass: 'bg-danger text-white',
            icon: 'fas fa-times-circle'
        }
    };
    
    const config = configs[type] || configs['error'];
    
    header.className = 'card-header ' + config.headerClass;
    titleElement.innerHTML = `<i class="${config.icon} me-2"></i>${title}`;
    body.innerHTML = content;
    
    container.classList.remove('d-none');
    
    // Auto-ocultar después de 10 segundos para loading y success
    if (type === 'loading') {
        // No auto-ocultar loading
    } else if (type === 'success') {
        setTimeout(() => {
            container.classList.add('d-none');
        }, 10000);
    }
}

// Manejar Enter en código manual
document.getElementById('codigoManual').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        validateManualCode();
    }
});

// Cargar estadísticas al inicio
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    
    // Actualizar estadísticas cada 30 segundos
    setInterval(loadStats, 30000);
});

// Limpiar recursos al salir
window.addEventListener('beforeunload', function() {
    stopCamera();
});
</script>

<style>
.scanner-container {
    position: relative;
}

#video {
    border: 2px solid var(--canaco-green);
    border-radius: 8px;
}

.card-metric {
    transition: transform 0.2s;
}

.card-metric:hover {
    transform: translateY(-2px);
}

.btn-group .btn.active {
    background-color: var(--canaco-green);
    border-color: var(--canaco-green);
    color: white;
}
</style>

</style>