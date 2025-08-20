<?php $pageTitle = 'Página no encontrada'; ?>

<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 60vh;">
    <div class="text-center">
        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 5rem;"></i>
        <h1 class="display-1 fw-bold">404</h1>
        <h2 class="mb-3">Página no encontrada</h2>
        <p class="lead text-muted mb-4">
            Lo sentimos, la página que buscas no existe o ha sido movida.
        </p>
        <div class="d-flex justify-content-center gap-2">
            <a href="<?php echo BASE_URL; ?>dashboard" class="btn btn-canaco">
                <i class="fas fa-home me-2"></i>
                Ir al Dashboard
            </a>
            <button onclick="history.back()" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Regresar
            </button>
        </div>
    </div>
</div>