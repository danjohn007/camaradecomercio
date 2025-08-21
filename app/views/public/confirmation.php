<?php 
$pageTitle = 'Confirmación de Registro - ' . htmlspecialchars($registro['evento_titulo']); 
?>

<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <div class="container">
        <!-- Success Message -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <div class="alert alert-success text-center">
                    <i class="fas fa-check-circle fa-3x mb-3" style="color: var(--canaco-green);"></i>
                    <h2 class="mb-2">¡Registro Exitoso!</h2>
                    <p class="mb-0">Tu boleto ha sido generado correctamente.</p>
                </div>
            </div>
        </div>
        
        <!-- Ticket Preview -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-canaco text-white text-center">
                        <h3 class="mb-0">
                            <i class="fas fa-ticket-alt me-2"></i>
                            Tu Boleto Digital
                        </h3>
                    </div>
                    <div class="card-body text-center">
                        <!-- QR Code -->
                        <div class="mb-4">
                            <div class="bg-light p-4 rounded mx-auto" style="width: fit-content;">
                                <div id="qrcode"></div>
                            </div>
                            <p class="small text-muted mt-2">
                                Código único: <strong><?php echo htmlspecialchars($registro['codigo_unico']); ?></strong>
                            </p>
                        </div>
                        
                        <!-- Event Information -->
                        <div class="row text-start">
                            <div class="col-md-6">
                                <h5 class="text-canaco">Evento:</h5>
                                <p><?php echo htmlspecialchars($registro['evento_titulo']); ?></p>
                                
                                <h5 class="text-canaco">Fecha y Hora:</h5>
                                <p>
                                    <i class="fas fa-calendar me-2"></i>
                                    <?php echo date('d/m/Y H:i', strtotime($registro['fecha_evento'])); ?>
                                </p>
                                
                                <h5 class="text-canaco">Ubicación:</h5>
                                <p>
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    <?php echo htmlspecialchars($registro['ubicacion']); ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-canaco">Registrado a nombre de:</h5>
                                <p><?php echo htmlspecialchars($registro['nombre_registrante']); ?></p>
                                
                                <h5 class="text-canaco">Email de confirmación:</h5>
                                <p><?php echo htmlspecialchars($registro['email_registrante']); ?></p>
                                
                                <h5 class="text-canaco">Estado:</h5>
                                <p>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>
                                        Registrado
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Instructions -->
                        <div class="alert alert-info mt-4">
                            <h6><i class="fas fa-info-circle me-2"></i>Instrucciones importantes:</h6>
                            <ul class="text-start mb-0">
                                <li>Recibirás un correo electrónico con tu boleto en formato PDF.</li>
                                <li>Presenta este código QR en el evento para tu acceso.</li>
                                <li>Guarda este código, puedes acceder a él en cualquier momento.</li>
                                <li>Si tienes alguna duda, contacta al organizador del evento.</li>
                            </ul>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                            <button type="button" class="btn btn-canaco" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>
                                Imprimir Boleto
                            </button>
                            <a href="<?php echo BASE_URL; ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i>
                                Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Generation -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate QR code
    const qrCodeContainer = document.getElementById('qrcode');
    const codigoUnico = '<?php echo htmlspecialchars($registro['codigo_unico']); ?>';
    
    QRCode.toCanvas(qrCodeContainer, codigoUnico, {
        width: 200,
        height: 200,
        color: {
            dark: '#4a7c59',
            light: '#ffffff'
        }
    }, function (error) {
        if (error) {
            console.error('Error generating QR code:', error);
            qrCodeContainer.innerHTML = '<div class="text-muted">Error generando código QR</div>';
        }
    });
});
</script>

<style>
@media print {
    .btn, .alert-info {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .card-header {
        background-color: #4a7c59 !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
}
</style>